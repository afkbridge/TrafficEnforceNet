// =======================================================
// TRAFFICENFORCENET
// ENFORCER - ISSUE TICKET JAVASCRIPT
// =======================================================

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    // ===================================================
    // FIELD INPUT RULES (single source of truth)
    // strip = characters that are NOT allowed
    // ===================================================

    const NAME_STRIP = /[^\p{L}\s.'\-]/gu;
    const OTHER_TEXT_STRIP = /[^\p{L}\p{N}\s,.\-\/()'&]/gu;

    const FIELD_RULES = {
        first_name: { label: "First name", strip: NAME_STRIP, min: 2, max: 50, letter: true, hint: "letters, spaces, . ' - only" },
        middle_name: { label: "Middle name", strip: NAME_STRIP, min: 1, max: 50, letter: true, hint: "letters, spaces, . ' - only" },
        last_name: { label: "Last name", strip: NAME_STRIP, min: 2, max: 50, letter: true, hint: "letters, spaces, . ' - only" },
        owner_name: { label: "Vehicle owner", strip: /[^\p{L}\s.,'&\-]/gu, min: 2, max: 100, letter: true, hint: "letters, spaces, . , ' & - only" },
        license_number: { label: "License number", strip: /[^A-Za-z0-9\-]/g, upper: true, min: 5, max: 20, hint: "letters, numbers and - only" },
        address: { label: "Address", strip: /[^\p{L}\p{N}\s,.\-#\/'()]/gu, min: 5, max: 255, letter: true, hint: "letters, numbers and , . - # / ' ( ) only" },
        plate_number: { label: "Plate number", strip: /[^A-Za-z0-9 \-]/g, upper: true, min: 3, max: 10, hint: "letters, numbers, space and - only" },
        region_number: { label: "Region number", strip: /[^A-Za-z0-9\-]/g, upper: true, min: 1, max: 20, hint: "letters, numbers and - only" },
        other_vehicle_type: { label: "Vehicle type", strip: /[^\p{L}\p{N}\s.\-\/]/gu, min: 2, max: 50, letter: true, hint: "letters, numbers and . - / only" },
        other_violation: { label: "Violation", strip: OTHER_TEXT_STRIP, min: 3, max: 150, letter: true, hint: "letters, numbers and , . - / ( ) ' & only" },
    };

    // Dynamic "additional other violation" inputs use the same rule
    const ADDITIONAL_OTHER_SELECTOR = 'input[name="additional_other_violation_names[]"]';

    function sanitizeByRule(rule, value) {
        let v = String(value ?? "").replace(rule.strip, "");
        v = v.replace(/\s{2,}/g, " ").replace(/^\s+/, "");
        if (rule.upper) v = v.toUpperCase();
        if (rule.max) v = v.slice(0, rule.max);
        return v;
    }

    // Returns an error message, or "" if the value is fine
    function checkByRule(rule, value) {
        const v = String(value ?? "").trim();
        if (v === "") return "";
        if (rule.min && v.length < rule.min) {
            return `${rule.label} must be at least ${rule.min} characters.`;
        }
        if (rule.letter && !/\p{L}/u.test(v)) {
            return `${rule.label} must contain letters (${rule.hint}).`;
        }
        return "";
    }

    function attachRule(field, rule) {
        if (!field || field.dataset.ruleAttached) return;
        field.dataset.ruleAttached = "1";
        if (rule.max) field.maxLength = rule.max;

        field.addEventListener("input", function () {
            const cleaned = sanitizeByRule(rule, field.value);
            if (cleaned !== field.value) field.value = cleaned;
        });

        field.addEventListener("blur", function () {
            field.value = field.value.trim();
        });
    }

    // ===================================================
    // SERVICE WORKER (lets the page open while offline)
    // sw.js must be placed in /public so its scope is "/"
    // ===================================================

    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.register("/sw.js").catch(function (error) {
            console.warn("Service worker registration failed:", error);
        });
    }

    // ===================================================
    // HELPER - GET ELEMENT
    // ===================================================

    function getElement(id) {
        return document.getElementById(id);
    }

    // ===================================================
    // HELPER - ONLY SET VALUE IF OCR FOUND SOMETHING
    // OCR values are cleaned with the same rules as typing
    // ===================================================

    function setFieldValue(id, value) {
        const field = getElement(id);

        if (!field) {
            return;
        }

        // DO NOT LET OCR WRITE INTO A FIELD MARKED AS
        // "NO LICENSE" OR "NO PLATE NUMBER"
        if (id === "license_number" && getElement("no_license")?.checked) {
            return;
        }

        if (id === "plate_number" && getElement("no_plate")?.checked) {
            return;
        }

        if (
            value === null ||
            value === undefined ||
            String(value).trim() === ""
        ) {
            field.value = "";
            return;
        }

        const rule = FIELD_RULES[id];

        field.value = rule
            ? sanitizeByRule(rule, value).trim()
            : String(value).trim();
    }

    // ===================================================
    // CONNECTION STATUS
    // ===================================================

    function updateConnectionStatus() {
        const status = getElement("connectionStatus");
        const icon = getElement("connectionStatusIcon");
        const text = getElement("connectionStatusText");

        if (!status || !icon || !text) {
            return;
        }

        if (navigator.onLine) {
            status.className =
                "bg-green-50 border border-green-200 text-green-700 rounded-2xl px-4 py-3 text-sm font-medium flex items-center gap-2";

            icon.textContent = "●";
            text.textContent = "Online - Data can be synchronized";
        } else {
            status.className =
                "bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-2xl px-4 py-3 text-sm font-medium flex items-center gap-2";

            icon.textContent = "●";
            text.textContent =
                "Offline - Data will be saved for synchronization";
        }
    }

    window.addEventListener("online", updateConnectionStatus);
    window.addEventListener("offline", updateConnectionStatus);

    updateConnectionStatus();

    // ===================================================
    // FORM
    // ===================================================

    const form = getElement("issueTicketForm");

    // Attach rules to static fields
    Object.keys(FIELD_RULES).forEach(function (id) {
        attachRule(getElement(id), FIELD_RULES[id]);
    });

    // Attach rule to dynamically added "Others" inputs
    if (form) {
        form.addEventListener("input", function (event) {
            const t = event.target;

            if (t.matches && t.matches(ADDITIONAL_OTHER_SELECTOR)) {
                t.maxLength = FIELD_RULES.other_violation.max;

                const cleaned = sanitizeByRule(
                    FIELD_RULES.other_violation,
                    t.value
                );

                if (cleaned !== t.value) t.value = cleaned;
            }
        });
    }

    // Birth date: no future dates, nothing before 1900
    const birthDateInput = getElement("birth_date");

    if (birthDateInput) {
        const d = new Date();
        const today =
            d.getFullYear() +
            "-" +
            String(d.getMonth() + 1).padStart(2, "0") +
            "-" +
            String(d.getDate()).padStart(2, "0");

        birthDateInput.max = today;
        birthDateInput.min = "1900-01-01";
    }

    // Remarks length
    const remarksInput = getElement("remarks");

    if (remarksInput) {
        remarksInput.maxLength = 500;
    }

    // ===================================================
    // SUBMISSION PROCESSING STATE
    // ===================================================

    let ticketSubmissionProcessing = false;
    let ticketProcessingOverlay = null;
    let ticketSubmitButtons = [];
    let ticketSubmitButtonStates = [];

    // ===================================================
    // FIND SUBMIT BUTTONS
    // ===================================================

    function getTicketSubmitButtons() {
        if (!form) {
            return [];
        }

        return Array.from(
            form.querySelectorAll('button[type="submit"], input[type="submit"]')
        );
    }

    // ===================================================
    // CREATE PROCESSING OVERLAY
    // ===================================================

    function createTicketProcessingOverlay() {
        if (ticketProcessingOverlay) {
            return ticketProcessingOverlay;
        }

        const overlay = document.createElement("div");

        overlay.id = "ticketProcessingOverlay";

        overlay.className =
            "fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm px-5";

        overlay.setAttribute("role", "dialog");
        overlay.setAttribute("aria-modal", "true");
        overlay.setAttribute("aria-live", "polite");

        overlay.innerHTML = `
            <div
                class="w-full max-w-sm rounded-3xl bg-white shadow-2xl border border-gray-100 px-6 py-7 text-center"
            >
                <div
                    id="ticketProcessingSpinner"
                    class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50"
                >
                    <div
                        class="h-9 w-9 animate-spin rounded-full border-4 border-blue-100 border-t-blue-600"
                        aria-hidden="true"
                    ></div>
                </div>

                <div
                    id="ticketProcessingIcon"
                    class="hidden mx-auto mb-5 h-16 w-16 items-center justify-center rounded-full bg-green-50 text-green-600 text-3xl font-bold"
                >
                    ✓
                </div>

                <div
                    id="ticketProcessingErrorIcon"
                    class="hidden mx-auto mb-5 h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-600 text-3xl font-bold"
                >
                    ✕
                </div>

                <h2
                    id="ticketProcessingTitle"
                    class="text-lg font-bold text-gray-900"
                >
                    Processing Citation Ticket
                </h2>

                <p
                    id="ticketProcessingMessage"
                    class="mt-2 text-sm leading-6 text-gray-500"
                >
                    Please wait while the citation ticket is being processed...
                </p>

                <div
                    id="ticketProcessingProgress"
                    class="mt-5"
                >
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                        <div
                            class="h-full w-1/2 rounded-full bg-blue-600 animate-pulse"
                        ></div>
                    </div>

                    <p
                        id="ticketProcessingStatus"
                        class="mt-3 text-xs font-medium text-blue-600"
                    >
                        Please do not close or refresh this page.
                    </p>
                </div>

                <button
                    id="ticketProcessingCloseButton"
                    type="button"
                    class="hidden mt-5 w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    Close
                </button>
            </div>
        `;

        document.body.appendChild(overlay);

        ticketProcessingOverlay = overlay;

        return overlay;
    }

    // ===================================================
    // SET PROCESSING BUTTON STATE
    // ===================================================

    function disableTicketSubmitButtons() {
        ticketSubmitButtons = getTicketSubmitButtons();

        ticketSubmitButtonStates = ticketSubmitButtons.map(function (button) {
            return {
                button: button,
                disabled: button.disabled,
                text: button.tagName === "INPUT" ? button.value : button.innerHTML,
            };
        });

        ticketSubmitButtons.forEach(function (button) {
            button.disabled = true;
            button.setAttribute("aria-disabled", "true");
            button.classList.add("opacity-70", "cursor-not-allowed");

            if (button.tagName === "INPUT") {
                button.value = "Processing...";
            } else {
                button.innerHTML = `
                    <span class="inline-flex items-center justify-center gap-2">
                        <span
                            class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
                        ></span>
                        <span>Processing...</span>
                    </span>
                `;
            }
        });
    }

    // ===================================================
    // RESTORE SUBMIT BUTTON STATE
    // ===================================================

    function restoreTicketSubmitButtons() {
        ticketSubmitButtonStates.forEach(function (state) {
            if (!state.button) {
                return;
            }

            state.button.disabled = state.disabled;
            state.button.removeAttribute("aria-disabled");
            state.button.classList.remove("opacity-70", "cursor-not-allowed");

            if (state.button.tagName === "INPUT") {
                state.button.value = state.text;
            } else {
                state.button.innerHTML = state.text;
            }
        });

        ticketSubmitButtons = [];
        ticketSubmitButtonStates = [];
    }

    // ===================================================
    // UPDATE PROCESSING OVERLAY
    // ===================================================

    function updateTicketProcessingOverlay(type, title, message, statusMessage) {
        const overlay = createTicketProcessingOverlay();

        const spinner = overlay.querySelector("#ticketProcessingSpinner");
        const successIcon = overlay.querySelector("#ticketProcessingIcon");
        const errorIcon = overlay.querySelector("#ticketProcessingErrorIcon");
        const titleElement = overlay.querySelector("#ticketProcessingTitle");
        const messageElement = overlay.querySelector("#ticketProcessingMessage");
        const progress = overlay.querySelector("#ticketProcessingProgress");
        const statusElement = overlay.querySelector("#ticketProcessingStatus");
        const closeButton = overlay.querySelector("#ticketProcessingCloseButton");

        if (titleElement) {
            titleElement.innerText = title;
        }

        if (messageElement) {
            messageElement.innerText = message;
        }

        if (statusElement) {
            statusElement.innerText = statusMessage || "";
        }

        if (spinner) {
            spinner.classList.add("hidden");
        }

        if (successIcon) {
            successIcon.classList.add("hidden");
            successIcon.classList.remove("flex");
        }

        if (errorIcon) {
            errorIcon.classList.add("hidden");
            errorIcon.classList.remove("flex");
        }

        if (progress) {
            progress.classList.remove("hidden");
        }

        if (closeButton) {
            closeButton.classList.add("hidden");
            closeButton.onclick = null;
        }

        if (type === "loading") {
            if (spinner) {
                spinner.classList.remove("hidden");
            }

            if (progress) {
                progress.classList.remove("hidden");
            }
        }

        if (type === "success") {
            if (successIcon) {
                successIcon.classList.remove("hidden");
                successIcon.classList.add("flex");
            }

            if (progress) {
                progress.classList.add("hidden");
            }

            if (closeButton) {
                closeButton.classList.remove("hidden");
                closeButton.onclick = function () {
                    hideTicketProcessingOverlay();
                };
            }
        }

        if (type === "error") {
            if (errorIcon) {
                errorIcon.classList.remove("hidden");
                errorIcon.classList.add("flex");
            }

            if (progress) {
                progress.classList.add("hidden");
            }

            if (closeButton) {
                closeButton.classList.remove("hidden");
                closeButton.onclick = function () {
                    hideTicketProcessingOverlay();
                };
            }
        }

        overlay.classList.remove("hidden");
    }

    // ===================================================
    // SHOW PROCESSING OVERLAY
    // ===================================================

    function showTicketProcessing(title, message, statusMessage) {
        ticketSubmissionProcessing = true;

        disableTicketSubmitButtons();

        updateTicketProcessingOverlay(
            "loading",
            title || "Processing Citation Ticket",
            message || "Please wait while the citation ticket is being processed...",
            statusMessage || "Please do not close or refresh this page."
        );
    }

    // ===================================================
    // HIDE PROCESSING OVERLAY
    // ===================================================

    function hideTicketProcessingOverlay() {
        if (ticketProcessingOverlay) {
            ticketProcessingOverlay.classList.add("hidden");
        }

        ticketSubmissionProcessing = false;

        restoreTicketSubmitButtons();
    }

    // ===================================================
    // SHOW PROCESSING SUCCESS
    // ===================================================

    function showTicketProcessingSuccess(title, message) {
        updateTicketProcessingOverlay(
            "success",
            title || "Ticket Saved Offline",
            message ||
                "Your citation ticket has been saved on this device and will automatically synchronize when the connection is restored.",
            "The ticket is waiting for synchronization."
        );
    }

    // ===================================================
    // SHOW PROCESSING ERROR
    // ===================================================

    function showTicketProcessingError(message) {
        updateTicketProcessingOverlay(
            "error",
            "Unable to Process Ticket",
            message || "The citation ticket could not be saved. Please try again.",
            "You may close this message and try again."
        );
    }

    // ===================================================
    // NO LICENSE / NO PLATE NUMBER
    // ===================================================

    const noLicenseCheckbox = getElement("no_license");
    const licenseNumberInput = getElement("license_number");
    const licenseRequiredMark = getElement("licenseRequiredMark");

    const noPlateCheckbox = getElement("no_plate");
    const plateNumberInput = getElement("plate_number");
    const plateRequiredMark = getElement("plateRequiredMark");

    // ===================================================
    // UPDATE LICENSE FIELD STATE
    // ===================================================

    function updateLicenseFieldState() {
        if (!licenseNumberInput || !noLicenseCheckbox) {
            return;
        }

        if (noLicenseCheckbox.checked) {
            licenseNumberInput.value = "";
            licenseNumberInput.disabled = true;
            licenseNumberInput.required = false;

            if (licenseRequiredMark) {
                licenseRequiredMark.classList.add("hidden");
            }
        } else {
            licenseNumberInput.disabled = false;
            licenseNumberInput.required = true;

            if (licenseRequiredMark) {
                licenseRequiredMark.classList.remove("hidden");
            }
        }

        licenseNumberInput.setCustomValidity("");
    }

    // ===================================================
    // UPDATE PLATE FIELD STATE
    // ===================================================

    function updatePlateFieldState() {
        if (!plateNumberInput || !noPlateCheckbox) {
            return;
        }

        if (noPlateCheckbox.checked) {
            plateNumberInput.value = "";
            plateNumberInput.disabled = true;
            plateNumberInput.required = false;

            if (plateRequiredMark) {
                plateRequiredMark.classList.add("hidden");
            }
        } else {
            plateNumberInput.disabled = false;
            plateNumberInput.required = true;

            if (plateRequiredMark) {
                plateRequiredMark.classList.remove("hidden");
            }
        }

        plateNumberInput.setCustomValidity("");
    }

    // ===================================================
    // LICENSE CHECKBOX
    // ===================================================

    if (noLicenseCheckbox) {
        noLicenseCheckbox.addEventListener("change", function () {
            updateLicenseFieldState();
            console.log("No License:", this.checked);
        });
    }

    // ===================================================
    // PLATE CHECKBOX
    // ===================================================

    if (noPlateCheckbox) {
        noPlateCheckbox.addEventListener("change", function () {
            updatePlateFieldState();
            console.log("No Plate Number:", this.checked);
        });
    }

    // ===================================================
    // INITIALIZE LICENSE / PLATE STATES
    // ===================================================

    updateLicenseFieldState();
    updatePlateFieldState();

    // ===================================================
    // VEHICLE TYPE - "OTHERS" MANUAL INPUT (FIXED)
    // Works for ANY chosen vehicle type, with or without OCR
    // ===================================================

    const vehicleType = getElement("vehicle_type");
    const otherVehicleTypeContainer = getElement("otherVehicleTypeContainer");
    const otherVehicleTypeInput = getElement("other_vehicle_type");

    function isOtherVehicleTypeSelected() {
        if (!vehicleType) {
            return false;
        }

        const value = String(vehicleType.value || "").trim().toLowerCase();

        if (value === "others" || value === "other") {
            return true;
        }

        const selected = vehicleType.options[vehicleType.selectedIndex];

        return (
            !!selected &&
            String(selected.textContent || "").trim().toLowerCase() === "others"
        );
    }

    function toggleOtherVehicleType() {
        if (!vehicleType) {
            return;
        }

        const showOther = isOtherVehicleTypeSelected();

        if (otherVehicleTypeContainer) {
            otherVehicleTypeContainer.classList.toggle("hidden", !showOther);
            otherVehicleTypeContainer.style.display = showOther ? "block" : "none";
        }

        if (otherVehicleTypeInput) {
            otherVehicleTypeInput.required = showOther;

            if (!showOther) {
                otherVehicleTypeInput.value = "";
            }
        }
    }

    if (vehicleType) {
        vehicleType.addEventListener("change", toggleOtherVehicleType);
        vehicleType.addEventListener("input", toggleOtherVehicleType);
    }

    toggleOtherVehicleType();

    // ===================================================
    // EVIDENCE PHOTO - MULTIPLE PHOTO ATTACHMENT
    // ===================================================

    const evidenceInput = getElement("evidence_images");
    const evidencePreviewContainer = getElement("evidencePreviewContainer");
    const evidencePreview = getElement("evidencePreview");

    let selectedEvidenceFiles = [];
    let evidencePreviewUrls = [];

    // ===================================================
    // CLEAR PREVIEW URLS
    // ===================================================

    function clearEvidencePreviewUrls() {
        evidencePreviewUrls.forEach(function (url) {
            try {
                URL.revokeObjectURL(url);
            } catch (error) {
                console.warn("Unable to revoke evidence preview URL:", error);
            }
        });

        evidencePreviewUrls = [];
    }

    // ===================================================
    // UPDATE ACTUAL FILE INPUT
    // ===================================================

    function updateEvidenceInput() {
        if (!evidenceInput) {
            return;
        }

        try {
            const dataTransfer = new DataTransfer();

            selectedEvidenceFiles.forEach(function (file) {
                if (file instanceof File) {
                    dataTransfer.items.add(file);
                }
            });

            evidenceInput.files = dataTransfer.files;

            console.log("Updated evidence input:", Array.from(evidenceInput.files));
        } catch (error) {
            console.error("Unable to update evidence file input:", error);
        }
    }

    // ===================================================
    // DISPLAY EVIDENCE PREVIEW
    // ===================================================

    function displayEvidencePreview() {
        if (!evidencePreview || !evidencePreviewContainer) {
            return;
        }

        clearEvidencePreviewUrls();

        evidencePreview.innerHTML = "";

        if (selectedEvidenceFiles.length === 0) {
            evidencePreviewContainer.classList.add("hidden");
            return;
        }

        evidencePreviewContainer.classList.remove("hidden");

        selectedEvidenceFiles.forEach(function (file, index) {
            if (!file || !file.type || !file.type.startsWith("image/")) {
                return;
            }

            const previewUrl = URL.createObjectURL(file);

            evidencePreviewUrls.push(previewUrl);

            const wrapper = document.createElement("div");

            wrapper.className =
                "relative rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-sm";

            const image = document.createElement("img");

            image.src = previewUrl;
            image.alt = `Evidence photo ${index + 1}`;
            image.className = "w-full h-36 sm:h-40 object-cover";

            const removeButton = document.createElement("button");

            removeButton.type = "button";

            removeButton.className =
                "absolute top-2 right-2 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center text-sm font-bold shadow hover:bg-red-700";

            removeButton.innerHTML = "&times;";
            removeButton.title = "Remove this photo";

            removeButton.addEventListener("click", function (event) {
                event.preventDefault();
                event.stopPropagation();

                selectedEvidenceFiles.splice(index, 1);

                updateEvidenceInput();
                displayEvidencePreview();

                console.log("Evidence photo removed.");
                console.log("Remaining evidence photos:", selectedEvidenceFiles.length);
            });

            const overlay = document.createElement("div");

            overlay.className =
                "absolute bottom-0 left-0 right-0 bg-black/60 text-white px-2 py-1.5";

            const fileName = document.createElement("p");

            fileName.className = "text-[10px] leading-tight truncate";
            fileName.textContent = file.name || `Evidence photo ${index + 1}`;

            const fileSize = document.createElement("p");

            fileSize.className = "text-[9px] text-gray-200 mt-0.5";

            const sizeInKb = Math.max(1, Math.round(file.size / 1024));

            fileSize.textContent = `${sizeInKb} KB`;

            overlay.appendChild(fileName);
            overlay.appendChild(fileSize);

            const photoNumber = document.createElement("div");

            photoNumber.className =
                "absolute top-2 left-2 bg-black/60 text-white rounded-full px-2 py-1 text-[10px] font-semibold";

            photoNumber.textContent = `Photo ${index + 1}`;

            wrapper.appendChild(image);
            wrapper.appendChild(overlay);
            wrapper.appendChild(photoNumber);
            wrapper.appendChild(removeButton);

            evidencePreview.appendChild(wrapper);
        });

        const countMessage = document.createElement("p");

        countMessage.className = "col-span-full text-xs text-gray-500 mt-1";

        countMessage.textContent = `${selectedEvidenceFiles.length} evidence photo${
            selectedEvidenceFiles.length === 1 ? "" : "s"
        } attached`;

        evidencePreview.appendChild(countMessage);
    }

    // ===================================================
    // EVIDENCE PHOTO INPUT
    // ===================================================

    if (evidenceInput) {
        evidenceInput.addEventListener("change", function () {
            const newFiles = this.files ? Array.from(this.files) : [];

            console.log("New evidence files selected:", newFiles);

            if (newFiles.length === 0) {
                return;
            }

            newFiles.forEach(function (file) {
                if (!file || !file.type || !file.type.startsWith("image/")) {
                    console.warn("Skipped non-image evidence file:", file?.name);
                    return;
                }

                const alreadyExists = selectedEvidenceFiles.some(function (
                    existingFile
                ) {
                    return (
                        existingFile.name === file.name &&
                        existingFile.size === file.size &&
                        existingFile.lastModified === file.lastModified
                    );
                });

                if (alreadyExists) {
                    console.log("Duplicate evidence photo skipped:", file.name);
                    return;
                }

                selectedEvidenceFiles.push(file);
            });

            updateEvidenceInput();
            displayEvidencePreview();

            console.log("Total evidence photos:", selectedEvidenceFiles.length);
            console.log(
                "Evidence files currently attached:",
                Array.from(evidenceInput.files)
            );
        });
    } else {
        console.warn("Evidence image input #evidence_images was not found.");
    }

    // ===================================================
    // OCR STATUS (SHARED RENDERER)
    // ===================================================

    function renderOcrStatus(ids, type, title, message) {
        const badge = getElement(ids.badge);
        const status = getElement(ids.status);
        const icon = getElement(ids.icon);
        const ocrTitle = getElement(ids.title);
        const ocrMessage = getElement(ids.message);

        if (ocrTitle) {
            ocrTitle.innerText = title;
        }

        if (ocrMessage) {
            ocrMessage.innerText = message;
        }

        if (status) {
            status.classList.remove(
                "bg-blue-50",
                "bg-green-50",
                "bg-red-50",
                "bg-yellow-50"
            );

            if (type === "success") {
                status.classList.add("bg-green-50");
            } else if (type === "error") {
                status.classList.add("bg-red-50");
            } else if (type === "loading") {
                status.classList.add("bg-yellow-50");
            } else {
                status.classList.add("bg-blue-50");
            }
        }

        if (badge) {
            badge.classList.remove(
                "bg-blue-100",
                "bg-green-100",
                "bg-red-100",
                "bg-yellow-100",
                "text-blue-700",
                "text-green-700",
                "text-red-700",
                "text-yellow-700"
            );

            if (type === "success") {
                badge.classList.add("bg-green-100", "text-green-700");
                badge.innerText = "Completed";
            } else if (type === "error") {
                badge.classList.add("bg-red-100", "text-red-700");
                badge.innerText = "Error";
            } else if (type === "loading") {
                badge.classList.add("bg-yellow-100", "text-yellow-700");
                badge.innerText = "Processing";
            } else {
                badge.classList.add("bg-blue-100", "text-blue-700");
                badge.innerText = "Ready";
            }
        }

        if (icon) {
            if (type === "success") {
                icon.innerText = "✓";
            } else if (type === "error") {
                icon.innerText = "✕";
            } else if (type === "loading") {
                icon.innerText = "⏳";
            } else {
                icon.innerText = "ℹ";
            }
        }
    }

    // ===================================================
    // DRIVER'S LICENSE OCR STATUS
    // ===================================================

    function setDriverOcrStatus(type, title, message) {
        renderOcrStatus(
            {
                badge: "ocrBadge",
                status: "ocrStatus",
                icon: "ocrIcon",
                title: "ocrTitle",
                message: "ocrMessage",
            },
            type,
            title,
            message
        );
    }

    // ===================================================
    // CITATION TICKET OCR STATUS
    // ===================================================

    function setTicketOcrStatus(type, title, message) {
        renderOcrStatus(
            {
                badge: "ticketOcrBadge",
                status: "ticketOcrStatus",
                icon: "ticketOcrIcon",
                title: "ticketOcrTitle",
                message: "ticketOcrMessage",
            },
            type,
            title,
            message
        );
    }

    // ===================================================
    // NORMALIZE IMAGE BEFORE OCR
    // ===================================================
    // OCR.space free API rejects files over 1 MB.
    // Converts ANY image into a JPEG resized/compressed
    // until it is under the size limit.
    // ===================================================

    const OCR_MAX_SIDE = 1800;
    const OCR_MAX_BYTES = 900 * 1024;

    async function normalizeImageForOcr(file, outputName) {
        if (!file) {
            return file;
        }

        // Skip only files that are clearly not images.
        // An EMPTY type must still be normalized.
        if (file.type && !file.type.startsWith("image/")) {
            return file;
        }

        try {
            const loaded = await new Promise(function (resolve, reject) {
                const objectUrl = URL.createObjectURL(file);
                const image = new Image();

                image.onload = function () {
                    URL.revokeObjectURL(objectUrl);

                    resolve({
                        source: image,
                        width: image.naturalWidth,
                        height: image.naturalHeight,
                    });
                };

                image.onerror = function () {
                    URL.revokeObjectURL(objectUrl);

                    // Fallback decoder
                    if (window.createImageBitmap) {
                        createImageBitmap(file)
                            .then(function (bitmap) {
                                resolve({
                                    source: bitmap,
                                    width: bitmap.width,
                                    height: bitmap.height,
                                });
                            })
                            .catch(function () {
                                reject(new Error("Unable to open the camera image."));
                            });

                        return;
                    }

                    reject(new Error("Unable to open the camera image."));
                };

                image.src = objectUrl;
            });

            if (!loaded.width || !loaded.height) {
                throw new Error("Unable to read the camera image dimensions.");
            }

            let scale = Math.min(
                1,
                OCR_MAX_SIDE / loaded.width,
                OCR_MAX_SIDE / loaded.height
            );

            let quality = 0.85;
            let blob = null;

            for (let attempt = 0; attempt < 6; attempt++) {
                const canvas = document.createElement("canvas");

                canvas.width = Math.max(1, Math.round(loaded.width * scale));
                canvas.height = Math.max(1, Math.round(loaded.height * scale));

                const context = canvas.getContext("2d");

                if (!context) {
                    throw new Error("Unable to process the camera image.");
                }

                // White background
                context.fillStyle = "#FFFFFF";
                context.fillRect(0, 0, canvas.width, canvas.height);

                context.drawImage(loaded.source, 0, 0, canvas.width, canvas.height);

                blob = await new Promise(function (resolve) {
                    canvas.toBlob(resolve, "image/jpeg", quality);
                });

                if (blob && blob.size <= OCR_MAX_BYTES) {
                    break;
                }

                scale = scale * 0.8;
                quality = Math.max(0.6, quality - 0.07);
            }

            if (loaded.source && typeof loaded.source.close === "function") {
                loaded.source.close();
            }

            if (!blob) {
                return file;
            }

            console.log("OCR image normalized:", {
                originalName: file.name,
                originalType: file.type,
                originalSize: file.size,
                normalizedSize: blob.size,
            });

            return new File([blob], outputName, {
                type: "image/jpeg",
                lastModified: Date.now(),
            });
        } catch (imageError) {
            console.warn(
                "Image normalization failed. Using original file:",
                imageError
            );

            // Do not stop OCR if normalization fails.
            return file;
        }
    }

    // ===================================================
    // DRIVER'S LICENSE CAMERA
    // ===================================================

    window.openLicenseCamera = function () {
        const input = getElement("driver_license");

        if (!input) {
            console.error("Driver license input not found.");
            return;
        }

        // Reset so the change event always fires
        input.value = "";

        input.setAttribute("accept", "image/*");
        input.setAttribute("capture", "environment");

        input.click();
    };

    // ===================================================
    // DRIVER'S LICENSE FILE
    // ===================================================

    window.openLicenseFile = function () {
        const input = getElement("driver_license");

        if (!input) {
            console.error("Driver license input not found.");
            return;
        }

        // Reset so the change event always fires
        input.value = "";

        input.removeAttribute("capture");

        input.click();
    };

    // ===================================================
    // DRIVER'S LICENSE OCR
    // ===================================================

    window.processDriverLicense = async function (event) {
        const input = event.target;
        const file = input?.files?.[0];

        if (!file) {
            return;
        }

        const preview = getElement("licensePreview");
        const container = getElement("licensePreviewContainer");

        if (preview && container) {
            if (preview.src && preview.src.startsWith("blob:")) {
                URL.revokeObjectURL(preview.src);
            }

            preview.src = URL.createObjectURL(file);

            container.classList.remove("hidden");
        }

        setDriverOcrStatus(
            "loading",
            "Reading driver's license...",
            "Please wait while the system extracts the driver information."
        );

        try {
            // NORMALIZE IMAGE BEFORE OCR
            const ocrFile = await normalizeImageForOcr(file, "driver-license.jpg");

            const formData = new FormData();

            formData.append(
                "driver_license",
                ocrFile,
                ocrFile.name || "driver-license.jpg"
            );

            console.log("Sending driver license OCR image:", {
                name: ocrFile.name,
                type: ocrFile.type,
                size: ocrFile.size,
            });

            const response = await fetch("/enforcer/ocr/driver-license", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    Accept: "application/json",
                },
                credentials: "same-origin",
            });

            console.log("Driver License OCR HTTP status:", response.status);

            let data = null;

            try {
                data = await response.json();
            } catch (jsonError) {
                console.error(
                    "Driver License OCR response was not valid JSON:",
                    jsonError
                );

                throw new Error(
                    `OCR server returned HTTP ${response.status}, but the response could not be read.`
                );
            }

            if (!response.ok || !data.success) {
                throw new Error(data.message || "Driver's license OCR failed.");
            }

            const result = data.data || {};

            setFieldValue("first_name", result.first_name);
            setFieldValue("middle_name", result.middle_name);
            setFieldValue("last_name", result.last_name);
            setFieldValue("license_number", result.license_number);
            setFieldValue("address", result.address);
            setFieldValue("birth_date", result.birth_date);

            setDriverOcrStatus(
                "success",
                "Driver's license scanned",
                data.message || "Driver information was extracted successfully."
            );

            console.log("Driver License OCR:", result);
        } catch (error) {
            console.error("Driver License OCR Error:", error);

            setDriverOcrStatus(
                "error",
                "OCR failed",
                error.message || "Unable to read the driver's license."
            );
        }
    };

    // ===================================================
    // CITATION TICKET CAMERA
    // ===================================================

    window.openTicketCamera = function () {
        const input = getElement("ticket_image");

        if (!input) {
            console.error("Ticket image input not found.");
            return;
        }

        // Reset so the change event always fires
        input.value = "";

        input.setAttribute("accept", "image/*");
        input.setAttribute("capture", "environment");

        input.click();
    };

    // ===================================================
    // CITATION TICKET FILE
    // ===================================================

    window.openTicketFile = function () {
        const input = getElement("ticket_image");

        if (!input) {
            console.error("Ticket image input not found.");
            return;
        }

        // Reset so the change event always fires
        input.value = "";

        input.removeAttribute("capture");

        input.click();
    };

    // ===================================================
    // CITATION TICKET PREVIEW + OCR
    // ===================================================

    window.previewTicket = function (event) {
        const input = event.target;
        const file = input?.files?.[0];

        if (!file) {
            return;
        }

        const preview = getElement("ticketPreview");
        const container = getElement("ticketPreviewContainer");

        if (preview && container) {
            if (preview.src && preview.src.startsWith("blob:")) {
                URL.revokeObjectURL(preview.src);
            }

            preview.src = URL.createObjectURL(file);

            container.classList.remove("hidden");
        }

        console.log("CAMERA/FILE SELECTED:", {
            name: file.name,
            type: file.type,
            size: file.size,
            lastModified: file.lastModified,
        });

        processCitationTicket(file);
    };

    // ===================================================
    // CITATION TICKET OCR
    // ===================================================

    async function processCitationTicket(file) {
        if (!file) {
            return;
        }

        setTicketOcrStatus(
            "loading",
            "Reading citation ticket...",
            "Preparing the image and extracting the ticket information."
        );

        try {
            // NORMALIZE IMAGE BEFORE OCR
            const ocrFile = await normalizeImageForOcr(file, "citation-ticket.jpg");

            // CREATE FORM DATA
            const formData = new FormData();

            formData.append(
                "ticket_image",
                ocrFile,
                ocrFile.name || "citation-ticket.jpg"
            );

            console.log("Sending citation ticket OCR image:", {
                name: ocrFile.name,
                type: ocrFile.type,
                size: ocrFile.size,
            });

            // SEND TO LARAVEL OCR ENDPOINT
            const response = await fetch("/enforcer/ocr/citation-ticket", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    Accept: "application/json",
                },
                credentials: "same-origin",
            });

            console.log("Citation OCR HTTP status:", response.status);

            // READ RESPONSE SAFELY
            let data = null;

            try {
                data = await response.json();
            } catch (jsonError) {
                console.error(
                    "Citation OCR response was not valid JSON:",
                    jsonError
                );

                throw new Error(
                    `OCR server returned HTTP ${response.status}, but the response could not be read.`
                );
            }

            // HANDLE OCR FAILURE
            if (!response.ok || !data.success) {
                console.error("Citation OCR server response:", data);

                throw new Error(
                    data.message ||
                        `Citation ticket OCR failed. Server returned HTTP ${response.status}.`
                );
            }

            // OCR SUCCESS
            const result = data.data || {};

            // TICKET INFORMATION
            setFieldValue("ticket_number", result.ticket_number);

            // DRIVER INFORMATION
            setFieldValue("first_name", result.first_name);
            setFieldValue("middle_name", result.middle_name);
            setFieldValue("last_name", result.last_name);
            setFieldValue("license_number", result.license_number);
            setFieldValue("address", result.address);
            setFieldValue("birth_date", result.birth_date);

            // VEHICLE INFORMATION
            setFieldValue("plate_number", result.plate_number);

            // OCR VEHICLE TYPE (uses the top-level toggle)
            if (vehicleType && result.vehicle_type) {
                const ocrVehicleType = String(result.vehicle_type).trim();

                const validVehicleTypes = [
                    "MC",
                    "MTC Private",
                    "MTC For Hire",
                    "PUJ",
                    "Private Vehicle",
                    "Others",
                ];

                const matchedVehicleType = validVehicleTypes.find(function (type) {
                    return type.toLowerCase() === ocrVehicleType.toLowerCase();
                });

                if (matchedVehicleType) {
                    vehicleType.value = matchedVehicleType;
                    toggleOtherVehicleType();
                }
            }

            setFieldValue("region_number", result.region_number);
            setFieldValue("owner_name", result.owner_name);

            // LOCATION IS NOT POPULATED BY OCR
            // GPS controls the location.
            // OCR must NOT overwrite the GPS location.

            // DETECTED VIOLATIONS
            handleDetectedViolations(result.violations || []);

            // OCR SUCCESS STATUS
            setTicketOcrStatus(
                "success",
                "Citation ticket scanned",
                data.message ||
                    "Readable information was extracted. Fields that could not be read clearly were left blank for manual entry."
            );

            // DEBUG INFORMATION
            console.log("Citation Ticket OCR:", result);

            if (data.raw_text) {
                console.log("Citation Ticket OCR Raw Text:", data.raw_text);
            }
        } catch (error) {
            // OCR ERROR
            console.error("Citation Ticket OCR Error:", error);

            setTicketOcrStatus(
                "error",
                "OCR failed",
                error.message || "Unable to read the citation ticket."
            );
        }
    }

    // ===================================================
    // HANDLE OCR DETECTED VIOLATIONS
    // ===================================================

    function handleDetectedViolations(violations) {
        if (!Array.isArray(violations) || violations.length === 0) {
            return;
        }

        const select = getElement("violation_type_id");
        const otherContainer = getElement("otherViolationContainer");
        const otherInput = getElement("other_violation");
        const remarks = getElement("remarks");

        if (!select) {
            return;
        }

        const detected = violations
            .map(function (violation) {
                return String(violation).replace(/\s+/g, " ").trim();
            })
            .filter(Boolean);

        if (detected.length === 0) {
            return;
        }

        const options = Array.from(select.options);
        const matchedOptions = [];

        detected.forEach(function (detectedViolation) {
            const cleanDetected = detectedViolation
                .toLowerCase()
                .replace(/\s+/g, " ")
                .trim();

            const match = options.find(function (option) {
                const optionText = option.textContent
                    .toLowerCase()
                    .replace(/\s+/g, " ")
                    .trim();

                return (
                    optionText.includes(cleanDetected) ||
                    cleanDetected.includes(optionText)
                );
            });

            if (match && match.value !== "other") {
                matchedOptions.push(match);
            }
        });

        if (matchedOptions.length > 0) {
            select.value = matchedOptions[0].value;

            if (otherContainer) {
                otherContainer.classList.add("hidden");
            }

            if (otherInput) {
                otherInput.required = false;
            }
        } else {
            const otherOption = options.find(function (option) {
                return option.value === "other";
            });

            if (otherOption) {
                select.value = "other";

                if (otherContainer) {
                    otherContainer.classList.remove("hidden");
                }

                if (otherInput) {
                    // Joined with ", " (";" is not an allowed character)
                    otherInput.value = sanitizeByRule(
                        FIELD_RULES.other_violation,
                        detected.join(", ")
                    );
                    otherInput.required = true;
                }
            }
        }

        if (remarks) {
            const detectedText = detected.join("; ");
            const existingRemarks = remarks.value.trim();
            const label = "OCR Detected Violations: ";

            if (!existingRemarks.includes(label)) {
                remarks.value = existingRemarks
                    ? `${existingRemarks}\n\n${label}${detectedText}`
                    : `${label}${detectedText}`;
            }
        }

        console.log("Detected violations:", detected);
    }

        // ===================================================
    // VIOLATION TYPE - OTHER
    // ===================================================

    const violationSelect = getElement("violation_type_id");
    const otherViolationContainer = getElement("otherViolationContainer");
    const otherViolationInput = getElement("other_violation");

    function updatePrimaryOtherViolation() {
        if (!violationSelect || !otherViolationContainer) {
            return;
        }

        if (violationSelect.value === "other") {
            otherViolationContainer.classList.remove("hidden");

            if (otherViolationInput) {
                otherViolationInput.required = true;
            }
        } else {
            otherViolationContainer.classList.add("hidden");

            if (otherViolationInput) {
                otherViolationInput.required = false;
                otherViolationInput.value = "";
            }
        }
    }

    if (violationSelect) {
        violationSelect.addEventListener(
            "change",
            updatePrimaryOtherViolation
        );
    }

    // ===================================================
    // TEMPORARY OCR IMAGE CLEANUP
    //
    // OCR images are ONLY used for reading.
    // They must NEVER be:
    // - submitted as citation evidence
    // - saved to IndexedDB
    // - synchronized later
    // - permanently stored
    //
    // Evidence photos use evidence_images[] and are
    // intentionally preserved.
    // ===================================================

    function clearTemporaryOcrInput(
        inputId,
        previewId = null,
        containerId = null
    ) {
        const input = getElement(inputId);

        if (input) {
            try {
                input.value = "";
            } catch (error) {
                console.warn(
                    `Unable to clear temporary OCR input "${inputId}":`,
                    error
                );
            }
        }

        const preview = previewId ? getElement(previewId) : null;

        if (preview) {
            try {
                if (
                    preview.src &&
                    typeof preview.src === "string" &&
                    preview.src.startsWith("blob:")
                ) {
                    URL.revokeObjectURL(preview.src);
                }
            } catch (error) {
                console.warn(
                    `Unable to revoke OCR preview URL for "${previewId}":`,
                    error
                );
            }

            preview.removeAttribute("src");
        }

        const container = containerId
            ? getElement(containerId)
            : null;

        if (container) {
            container.classList.add("hidden");
        }
    }

    function clearAllTemporaryOcrInputs() {
        clearTemporaryOcrInput(
            "driver_license",
            "licensePreview",
            "licensePreviewContainer"
        );

        clearTemporaryOcrInput(
            "ticket_image",
            "ticketPreview",
            "ticketPreviewContainer"
        );
    }

    // ===================================================
    // OFFLINE SYNC UI
    // ===================================================

    const offlinePendingBox = getElement("offlinePendingBox");
    const offlinePendingTitle = getElement("offlinePendingTitle");
    const offlinePendingMessage = getElement("offlinePendingMessage");
    const syncNowButton = getElement("syncNowButton");

    const offlineSyncingBox = getElement("offlineSyncingBox");
    const offlineSyncingMessage = getElement("offlineSyncingMessage");

    const offlineSuccessBox = getElement("offlineSuccessBox");
    const offlineSuccessMessage = getElement("offlineSuccessMessage");

    const offlineErrorBox = getElement("offlineErrorBox");
    const offlineErrorMessage = getElement("offlineErrorMessage");

    // ===================================================
    // INDEXEDDB SETTINGS
    // ===================================================

    const OFFLINE_DB_NAME = "TrafficEnforceNetDB";
    const OFFLINE_DB_VERSION = 1;
    const OFFLINE_STORE_NAME = "pendingTickets";

    let syncInProgress = false;

    // ===================================================
    // REQUIRED FIELD RULES
    // ===================================================

    function setupRequiredFields() {
        if (!form) {
            return;
        }

        const requiredFieldIds = [
            "first_name",
            "last_name",
            "violation_type_id",
        ];

        requiredFieldIds.forEach(function (id) {
            const field = getElement(id);

            if (field) {
                field.required = true;
            }
        });

        // LICENSE
        updateLicenseFieldState();

        // PLATE
        updatePlateFieldState();

        updatePrimaryOtherViolation();

        const additionalViolationSelects =
            form.querySelectorAll(
                'select[name="additional_violation_type_ids[]"]'
            );

        additionalViolationSelects.forEach(function (select) {
            select.required = true;

            const row =
                select.closest(".additional-violation-row") ||
                select.parentElement;

            const additionalOtherInput =
                row?.querySelector(
                    'input[name="additional_other_violation_names[]"]'
                );

            if (additionalOtherInput) {
                additionalOtherInput.required =
                    select.value === "other";
            }
        });
    }

    setupRequiredFields();

    // ===================================================
    // HANDLE DYNAMIC ADDITIONAL VIOLATIONS
    // ===================================================

    if (form) {
        form.addEventListener("change", function (event) {
            const target = event.target;

            if (
                target.matches(
                    'select[name="additional_violation_type_ids[]"]'
                )
            ) {
                target.required = true;

                const row =
                    target.closest(".additional-violation-row") ||
                    target.parentElement;

                const additionalOtherInput =
                    row?.querySelector(
                        'input[name="additional_other_violation_names[]"]'
                    );

                if (additionalOtherInput) {
                    additionalOtherInput.required =
                        target.value === "other";
                }
            }
        });

        if (window.MutationObserver) {
            const observer = new MutationObserver(function () {
                setupRequiredFields();
            });

            observer.observe(form, {
                childList: true,
                subtree: true,
            });
        }
    }

    // ===================================================
    // OPEN INDEXEDDB
    // ===================================================

    function openOfflineDatabase() {
        return new Promise(function (resolve, reject) {
            if (!window.indexedDB) {
                reject(
                    new Error(
                        "IndexedDB is not supported on this device."
                    )
                );
                return;
            }

            const request = indexedDB.open(
                OFFLINE_DB_NAME,
                OFFLINE_DB_VERSION
            );

            request.onupgradeneeded = function (event) {
                const db = event.target.result;

                if (
                    !db.objectStoreNames.contains(
                        OFFLINE_STORE_NAME
                    )
                ) {
                    db.createObjectStore(
                        OFFLINE_STORE_NAME,
                        {
                            keyPath: "id",
                            autoIncrement: true,
                        }
                    );
                }
            };

            request.onsuccess = function () {
                resolve(request.result);
            };

            request.onerror = function () {
                reject(
                    request.error ||
                        new Error(
                            "Unable to open offline storage."
                        )
                );
            };
        });
    }

    // ===================================================
    // SAVE TICKET OFFLINE
    //
    // IMPORTANT:
    // OCR inputs are explicitly excluded.
    //
    // Only actual evidence_images[] files are allowed
    // to be saved in IndexedDB.
    // ===================================================

    async function saveTicketOffline() {
        if (!form) {
            throw new Error(
                "Issue ticket form was not found."
            );
        }

        const formData = new FormData(form);

        // ===================================================
        // REMOVE TEMPORARY OCR IMAGES BEFORE SERIALIZATION
        // ===================================================

        formData.delete("driver_license");
        formData.delete("ticket_image");

        const fields = {};
        const files = {};

        for (const [name, value] of formData.entries()) {
            // ===================================================
            // EXTRA SAFETY:
            // Never allow OCR fields to enter offline storage.
            // ===================================================

            if (
                name === "driver_license" ||
                name === "ticket_image"
            ) {
                continue;
            }

            if (
                value instanceof File ||
                value instanceof Blob
            ) {
                if (!value.size) {
                    continue;
                }

                const fileData = {
                    name:
                        value.name ||
                        `${name}.file`,
                    type:
                        value.type ||
                        "application/octet-stream",
                    lastModified:
                        value.lastModified ||
                        Date.now(),
                    blob: value,
                };

                if (name.endsWith("[]")) {
                    if (!files[name]) {
                        files[name] = [];
                    }

                    files[name].push(fileData);
                } else {
                    files[name] = fileData;
                }
            } else {
                if (fields[name] === undefined) {
                    fields[name] = String(value);
                } else {
                    if (!Array.isArray(fields[name])) {
                        fields[name] = [
                            fields[name],
                        ];
                    }

                    fields[name].push(String(value));
                }
            }
        }

        // ===================================================
        // EXTRA SAFETY:
        // Only evidence_images[] should contain uploaded
        // image files for offline citation storage.
        //
        // Remove any accidental OCR file entries.
        // ===================================================

        delete files.driver_license;
        delete files.ticket_image;

        const db = await openOfflineDatabase();

        return new Promise(function (resolve, reject) {
            const transaction = db.transaction(
                OFFLINE_STORE_NAME,
                "readwrite"
            );

            const store =
                transaction.objectStore(
                    OFFLINE_STORE_NAME
                );

            const request = store.add({
                fields: fields,
                files: files,
                action: form.action,
                createdAt:
                    new Date().toISOString(),
            });

            request.onsuccess = function () {
                resolve(request.result);
            };

            request.onerror = function () {
                reject(
                    request.error ||
                        new Error(
                            "Unable to save ticket offline."
                        )
                );
            };

            transaction.oncomplete = function () {
                db.close();
            };
        });
    }

    // ===================================================
    // GET PENDING TICKETS
    // ===================================================

    async function getPendingTickets() {
        const db = await openOfflineDatabase();

        return new Promise(function (resolve, reject) {
            const transaction = db.transaction(
                OFFLINE_STORE_NAME,
                "readonly"
            );

            const store =
                transaction.objectStore(
                    OFFLINE_STORE_NAME
                );

            const request = store.getAll();

            request.onsuccess = function () {
                resolve(request.result || []);
            };

            request.onerror = function () {
                reject(
                    request.error ||
                        new Error(
                            "Unable to read pending tickets."
                        )
                );
            };

            transaction.oncomplete = function () {
                db.close();
            };
        });
    }

    // ===================================================
    // DELETE SYNCED TICKET
    // ===================================================

    async function deletePendingTicket(id) {
        const db = await openOfflineDatabase();

        return new Promise(function (resolve, reject) {
            const transaction = db.transaction(
                OFFLINE_STORE_NAME,
                "readwrite"
            );

            const store =
                transaction.objectStore(
                    OFFLINE_STORE_NAME
                );

            const request = store.delete(id);

            request.onsuccess = function () {
                resolve();
            };

            request.onerror = function () {
                reject(
                    request.error ||
                        new Error(
                            "Unable to remove synced ticket."
                        )
                );
            };

            transaction.oncomplete = function () {
                db.close();
            };
        });
    }

    // ===================================================
    // GET CSRF TOKEN
    // ===================================================

    function getCsrfToken() {
        const meta = document.querySelector(
            'meta[name="csrf-token"]'
        );

        if (meta) {
            const token =
                meta.getAttribute("content");

            if (token) {
                return token;
            }
        }

        const tokenInput =
            form?.querySelector(
                'input[name="_token"]'
            );

        return tokenInput?.value || "";
    }

    // ===================================================
    // BUILD FORMDATA FOR SYNC
    //
    // IMPORTANT:
    // Explicitly prevents OCR images from being
    // reconstructed from IndexedDB.
    // ===================================================

    function buildSyncFormData(ticket) {
        const syncFormData = new FormData();

        Object.entries(
            ticket.fields || {}
        ).forEach(function ([name, value]) {
            if (
                name === "_token" ||
                name === "driver_license" ||
                name === "ticket_image"
            ) {
                return;
            }

            if (Array.isArray(value)) {
                value.forEach(function (item) {
                    syncFormData.append(
                        name,
                        item
                    );
                });
            } else {
                syncFormData.append(
                    name,
                    value
                );
            }
        });

        const csrfToken = getCsrfToken();

        if (csrfToken) {
            syncFormData.append(
                "_token",
                csrfToken
            );
        }

        Object.entries(
            ticket.files || {}
        ).forEach(function ([name, fileData]) {
            // ===================================================
            // EXTRA OCR PROTECTION
            // ===================================================

            if (
                name === "driver_license" ||
                name === "ticket_image"
            ) {
                return;
            }

            if (Array.isArray(fileData)) {
                fileData.forEach(function (item) {
                    const file = new File(
                        [item.blob],
                        item.name,
                        {
                            type: item.type,
                            lastModified:
                                item.lastModified,
                        }
                    );

                    syncFormData.append(
                        name,
                        file
                    );
                });
            } else {
                const file = new File(
                    [fileData.blob],
                    fileData.name,
                    {
                        type: fileData.type,
                        lastModified:
                            fileData.lastModified,
                    }
                );

                syncFormData.append(
                    name,
                    file
                );
            }
        });

        return syncFormData;
    }

    // ===================================================
    // HIDE OFFLINE STATUS
    // ===================================================

    function hideOfflineStatus() {
        if (offlinePendingBox) {
            offlinePendingBox.classList.add(
                "hidden"
            );
        }

        if (offlineSyncingBox) {
            offlineSyncingBox.classList.add(
                "hidden"
            );
        }

        if (offlineSuccessBox) {
            offlineSuccessBox.classList.add(
                "hidden"
            );
        }

        if (offlineErrorBox) {
            offlineErrorBox.classList.add(
                "hidden"
            );
        }
    }

    // ===================================================
    // SHOW PENDING TICKETS
    // ===================================================

    function showPendingTickets(count) {
        if (!offlinePendingBox) {
            return;
        }

        offlinePendingBox.classList.remove(
            "hidden"
        );

        if (offlinePendingTitle) {
            offlinePendingTitle.innerText =
                `Pending Offline Tickets (${count})`;
        }

        if (offlinePendingMessage) {
            if (navigator.onLine) {
                offlinePendingMessage.innerText =
                    `${count} ticket${
                        count === 1 ? "" : "s"
                    } waiting to sync.`;
            } else {
                offlinePendingMessage.innerText =
                    `You are offline. ${count} ticket${
                        count === 1 ? "" : "s"
                    } saved on this device and waiting to sync.`;
            }
        }

        if (syncNowButton) {
            if (navigator.onLine) {
                syncNowButton.classList.remove(
                    "hidden"
                );

                syncNowButton.disabled = false;
                syncNowButton.innerText =
                    "Sync Now";
            } else {
                syncNowButton.classList.add(
                    "hidden"
                );

                syncNowButton.disabled = true;
            }
        }
    }

    // ===================================================
    // SHOW SYNCING
    // ===================================================

    function showSyncing(count) {
        hideOfflineStatus();

        if (offlineSyncingBox) {
            offlineSyncingBox.classList.remove(
                "hidden"
            );
        }

        if (offlineSyncingMessage) {
            offlineSyncingMessage.innerText =
                `Uploading ${count} pending ticket${
                    count === 1 ? "" : "s"
                }...`;
        }
    }

    // ===================================================
    // SHOW SUCCESS
    // ===================================================

    function showSyncSuccess(count) {
        hideOfflineStatus();

        if (offlineSuccessBox) {
            offlineSuccessBox.classList.remove(
                "hidden"
            );
        }

        if (offlineSuccessMessage) {
            offlineSuccessMessage.innerText =
                `${count} ticket${
                    count === 1 ? "" : "s"
                } synchronized successfully.`;
        }

        setTimeout(function () {
            updatePendingSyncCount();
        }, 3000);
    }

    // ===================================================
    // SHOW ERROR
    // ===================================================

    function showSyncError(message) {
        hideOfflineStatus();

        if (offlineErrorBox) {
            offlineErrorBox.classList.remove(
                "hidden"
            );
        }

        if (offlineErrorMessage) {
            offlineErrorMessage.innerText =
                message ||
                "Some tickets could not be synchronized.";
        }
    }

    // ===================================================
    // UPDATE PENDING COUNT
    // ===================================================

    async function updatePendingSyncCount() {
        try {
            const tickets =
                await getPendingTickets();

            const count = tickets.length;

            if (count === 0) {
                hideOfflineStatus();
                return;
            }

            showPendingTickets(count);
        } catch (error) {
            console.error(
                "Offline storage error:",
                error
            );

            showSyncError(
                error.message ||
                    "Unable to read offline tickets."
            );
        }
    }

    // ===================================================
    // SYNC ONE TICKET
    // ===================================================

    async function syncOneTicket(ticket) {
        if (!navigator.onLine) {
            throw new Error(
                "Internet connection is unavailable."
            );
        }

        const syncFormData =
            buildSyncFormData(ticket);

        const action =
            ticket.action ||
            form?.action ||
            "/enforcer/issue-ticket";

        const response = await fetch(action, {
            method: "POST",
            body: syncFormData,
            credentials: "same-origin",
            headers: {
                Accept: "application/json",
                "X-Requested-With":
                    "XMLHttpRequest",
            },
        });

        if (!response.ok) {
            let message =
                `Server returned HTTP ${response.status}.`;

            if (response.status === 419) {
                message =
                    "Session expired. Please reload the page and log in again, then tap Sync Now.";
            }

            try {
                const data =
                    await response.json();

                if (data.message) {
                    message = data.message;
                }

                if (data.errors) {
                    const firstError =
                        Object.values(
                            data.errors
                        )
                            .flat()
                            .find(Boolean);

                    if (firstError) {
                        message = firstError;
                    }
                }
            } catch (error) {
                // Response was not JSON.
            }

            throw new Error(message);
        }

        return true;
    }

    // ===================================================
    // SYNC ALL PENDING TICKETS
    // ===================================================

    async function syncPendingTickets() {
        if (syncInProgress) {
            return;
        }

        if (!navigator.onLine) {
            await updatePendingSyncCount();
            return;
        }

        syncInProgress = true;

        try {
            const tickets =
                await getPendingTickets();

            if (tickets.length === 0) {
                hideOfflineStatus();
                return;
            }

            showSyncing(tickets.length);

            let syncedCount = 0;
            let lastError = "";

            for (const ticket of tickets) {
                if (!navigator.onLine) {
                    lastError =
                        "Internet connection was lost during synchronization.";

                    break;
                }

                try {
                    await syncOneTicket(ticket);

                    await deletePendingTicket(
                        ticket.id
                    );

                    syncedCount++;
                } catch (error) {
                    console.error(
                        `Failed to sync ticket ${ticket.id}:`,
                        error
                    );

                    lastError =
                        error.message ||
                        "Unable to synchronize ticket.";

                    break;
                }
            }

            const remainingTickets =
                await getPendingTickets();

            const remainingCount =
                remainingTickets.length;

            if (remainingCount === 0) {
                if (syncedCount > 0) {
                    showSyncSuccess(
                        syncedCount
                    );
                } else {
                    hideOfflineStatus();
                }

                return;
            }

            showSyncError(
                `${remainingCount} ticket${
                    remainingCount === 1
                        ? ""
                        : "s"
                } remain pending. ${
                    lastError ||
                    "Please try again when the connection is stable."
                }`
            );

            setTimeout(function () {
                updatePendingSyncCount();
            }, 2500);
        } catch (error) {
            console.error(
                "Offline synchronization error:",
                error
            );

            showSyncError(
                error.message ||
                    "Unable to synchronize offline tickets."
            );
        } finally {
            syncInProgress = false;
        }
    }

    // ===================================================
    // MANUAL SYNC BUTTON
    // ===================================================

    if (syncNowButton) {
        syncNowButton.addEventListener(
            "click",
            async function (event) {
                event.preventDefault();

                if (syncInProgress) {
                    return;
                }

                if (!navigator.onLine) {
                    showSyncError(
                        "Internet connection is unavailable. Please reconnect and try again."
                    );

                    return;
                }

                await syncPendingTickets();
            }
        );
    }

    // ===================================================
    // VALIDATE FORM
    // ===================================================

    function validateTicketForm() {
        if (!form) {
            return false;
        }

        setupRequiredFields();

        // ALWAYS REQUIRED

        const requiredTextFieldIds = [
            "first_name",
            "last_name",
        ];

        for (const id of requiredTextFieldIds) {
            const field = getElement(id);

            if (
                field &&
                field.value.trim() === ""
            ) {
                field.setCustomValidity(
                    "Please fill out this field."
                );

                field.reportValidity();

                field.setCustomValidity("");

                return false;
            }

            if (field) {
                field.setCustomValidity("");
            }
        }

        // FORMAT / LENGTH RULES

        for (const id of Object.keys(
            FIELD_RULES
        )) {
            const field = getElement(id);

            if (!field || field.disabled) {
                continue;
            }

            // Skip hidden "Others" inputs
            if (
                field.offsetParent === null &&
                !field.required
            ) {
                continue;
            }

            const error = checkByRule(
                FIELD_RULES[id],
                field.value
            );

            if (error) {
                field.setCustomValidity(
                    error
                );

                field.reportValidity();

                field.setCustomValidity("");

                return false;
            }
        }

        for (const input of form.querySelectorAll(
            ADDITIONAL_OTHER_SELECTOR
        )) {
            const error = checkByRule(
                FIELD_RULES.other_violation,
                input.value
            );

            if (error) {
                input.setCustomValidity(
                    error
                );

                input.reportValidity();

                input.setCustomValidity("");

                return false;
            }
        }

        // BIRTH DATE

        if (
            birthDateInput &&
            birthDateInput.value
        ) {
            if (
                birthDateInput.max &&
                birthDateInput.value >
                    birthDateInput.max
            ) {
                birthDateInput.setCustomValidity(
                    "Birth date cannot be in the future."
                );

                birthDateInput.reportValidity();

                birthDateInput.setCustomValidity("");

                return false;
            }

            if (
                birthDateInput.value <
                "1900-01-01"
            ) {
                birthDateInput.setCustomValidity(
                    "Please enter a valid birth date."
                );

                birthDateInput.reportValidity();

                birthDateInput.setCustomValidity("");

                return false;
            }
        }

        // LICENSE VALIDATION

        if (
            noLicenseCheckbox &&
            !noLicenseCheckbox.checked
        ) {
            if (
                !licenseNumberInput ||
                licenseNumberInput.value.trim() === ""
            ) {
                if (licenseNumberInput) {
                    licenseNumberInput.disabled =
                        false;

                    licenseNumberInput.required =
                        true;

                    licenseNumberInput.setCustomValidity(
                        "Please enter the license number or select No License."
                    );

                    licenseNumberInput.reportValidity();

                    licenseNumberInput.setCustomValidity("");
                }

                return false;
            }
        }

        // PLATE VALIDATION

        if (
            noPlateCheckbox &&
            !noPlateCheckbox.checked
        ) {
            if (
                !plateNumberInput ||
                plateNumberInput.value.trim() === ""
            ) {
                if (plateNumberInput) {
                    plateNumberInput.disabled =
                        false;

                    plateNumberInput.required =
                        true;

                    plateNumberInput.setCustomValidity(
                        "Please enter the plate number or select No Plate Number."
                    );

                    plateNumberInput.reportValidity();

                    plateNumberInput.setCustomValidity("");
                }

                return false;
            }
        }

        // GENERAL FORM VALIDATION

        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }

        // PRIMARY VIOLATION

        const primaryViolation =
            getElement(
                "violation_type_id"
            );

        const primaryOther =
            getElement("other_violation");

        if (
            primaryViolation &&
            primaryViolation.value === "other"
        ) {
            if (
                !primaryOther ||
                primaryOther.value.trim() === ""
            ) {
                if (primaryOther) {
                    primaryOther.required = true;

                    primaryOther.setCustomValidity(
                        "Please specify the other violation."
                    );

                    primaryOther.reportValidity();

                    primaryOther.setCustomValidity("");
                }

                return false;
            }
        }

        // ADDITIONAL VIOLATIONS

        const additionalViolationSelects =
            form.querySelectorAll(
                'select[name="additional_violation_type_ids[]"]'
            );

        for (
            const select of additionalViolationSelects
        ) {
            select.required = true;

            if (!select.value) {
                select.reportValidity();
                return false;
            }

            if (select.value === "other") {
                const row =
                    select.closest(
                        ".additional-violation-row"
                    ) ||
                    select.parentElement;

                const otherInput =
                    row?.querySelector(
                        'input[name="additional_other_violation_names[]"]'
                    );

                if (
                    !otherInput ||
                    otherInput.value.trim() === ""
                ) {
                    if (otherInput) {
                        otherInput.required =
                            true;

                        otherInput.setCustomValidity(
                            "Please specify the other violation."
                        );

                        otherInput.reportValidity();

                        otherInput.setCustomValidity("");
                    }

                    return false;
                }
            }
        }

        return true;
    }

    // ===================================================
    // RESET FORM AFTER OFFLINE SAVE
    // ===================================================

    function resetTicketFormAfterOfflineSave() {
        if (!form) {
            return;
        }

        form.reset();

        // ===================================================
        // CLEAR TEMPORARY OCR IMAGES
        // ===================================================

        clearAllTemporaryOcrInputs();

        // form.reset() does not fire "change"

        toggleOtherVehicleType();

        selectedEvidenceFiles = [];

        clearEvidencePreviewUrls();

        if (evidencePreview) {
            evidencePreview.innerHTML = "";
        }

        if (evidencePreviewContainer) {
            evidencePreviewContainer.classList.add(
                "hidden"
            );
        }

        updateLicenseFieldState();
        updatePlateFieldState();
        setupRequiredFields();

        if (otherViolationContainer) {
            otherViolationContainer.classList.add(
                "hidden"
            );
        }

        if (otherViolationInput) {
            otherViolationInput.required = false;
            otherViolationInput.value = "";
        }
    }

    // ===================================================
    // SAVE TICKET LOCALLY (OFFLINE FLOW)
    //
    // alreadyProcessing = true when the overlay is already
    // open (online submit failed because of the network)
    // ===================================================

    async function saveTicketForLater(
        alreadyProcessing
    ) {
        if (alreadyProcessing) {
            updateTicketProcessingOverlay(
                "loading",
                "Saving Ticket Offline",
                "No connection to the server. Saving the citation ticket and evidence photos on this device...",
                "Saving ticket data to local storage..."
            );
        } else {
            showTicketProcessing(
                "Saving Ticket Offline",
                "Please wait while the citation ticket and evidence photos are being saved on this device.",
                "Saving ticket data to local storage..."
            );
        }

        try {
            console.log(
                "Offline ticket saving started."
            );

            // ===================================================
            // OCR FILES ARE NOT PART OF OFFLINE STORAGE.
            // They are cleared immediately before serialization.
            // ===================================================

            clearAllTemporaryOcrInputs();

            await saveTicketOffline();

            console.log(
                "Ticket successfully saved to IndexedDB."
            );

            updateTicketProcessingOverlay(
                "loading",
                "Ticket Saved Locally",
                "The ticket has been saved on this device. Checking pending synchronization status...",
                "Preparing the ticket for automatic synchronization."
            );

            await updatePendingSyncCount();

            const tickets =
                await getPendingTickets();

            const count = tickets.length;

            if (offlinePendingMessage) {
                offlinePendingMessage.innerText =
                    `Your ticket has been saved on this device. ${count} ticket${
                        count === 1
                            ? ""
                            : "s"
                    } waiting to sync.`;
            }

            console.log(
                "Ticket saved successfully for offline synchronization."
            );

            // RESET FORM

            resetTicketFormAfterOfflineSave();

            // SHOW SUCCESS

            showTicketProcessingSuccess(
                "Ticket Saved Offline",
                "Your citation ticket has been saved on this device and will automatically synchronize when the connection is restored."
            );
        } catch (error) {
            console.error(
                "Offline ticket save error:",
                error
            );

            showTicketProcessingError(
                error.message ||
                    "Unable to save the ticket on this device."
            );

            showSyncError(
                error.message ||
                    "Unable to save the ticket on this device."
            );
        }
    }

    // ===================================================
    // FORM SUBMISSION
    // ===================================================

    if (form) {
        form.addEventListener(
            "submit",
            async function (event) {
                // ===================================================
                // PREVENT DOUBLE SUBMISSION
                // ===================================================

                if (ticketSubmissionProcessing) {
                    event.preventDefault();

                    console.log(
                        "Ticket submission is already being processed."
                    );

                    return;
                }

                // ===================================================
                // VALIDATE FORM FIRST
                // ===================================================

                if (!validateTicketForm()) {
                    event.preventDefault();
                    return;
                }

                // ===================================================
                // ONLINE
                // ===================================================

                if (navigator.onLine) {
                    // We submit manually so the browser does not
                    // reuse the DataTransfer-generated file list.

                    event.preventDefault();

                    console.log(
                        "Submitting online with evidence photos:",
                        selectedEvidenceFiles.length
                    );

                    showTicketProcessing(
                        "Processing Citation Ticket",
                        "Please wait while the citation ticket and evidence are being submitted.",
                        "Preparing ticket data and evidence photos..."
                    );

                    try {
                        const onlineFormData =
                            new FormData(form);

                        // ===================================================
                        // CRITICAL OCR PROTECTION
                        //
                        // These are OCR-only images.
                        // They MUST NOT be sent to the citation
                        // submission endpoint.
                        // ===================================================

                        onlineFormData.delete(
                            "driver_license"
                        );

                        onlineFormData.delete(
                            "ticket_image"
                        );

                        // ===================================================
                        // REMOVE THE EVIDENCE FILES THAT CAME FROM THE
                        // NATIVE INPUT / DATATRANSFER FILELIST
                        // ===================================================

                        onlineFormData.delete(
                            "evidence_images[]"
                        );

                        // ===================================================
                        // ADD ONLY THE ORIGINAL SELECTED EVIDENCE FILES
                        // ===================================================

                        selectedEvidenceFiles.forEach(
                            function (file) {
                                if (
                                    file &&
                                    file instanceof File &&
                                    file.size > 0
                                ) {
                                    onlineFormData.append(
                                        "evidence_images[]",
                                        file,
                                        file.name
                                    );

                                    console.log(
                                        "Evidence file appended:",
                                        file.name,
                                        file.size,
                                        file.type
                                    );
                                }
                            }
                        );

                        // ===================================================
                        // EXTRA SAFETY CHECK
                        // Remove OCR files again immediately before fetch.
                        // ===================================================

                        onlineFormData.delete(
                            "driver_license"
                        );

                        onlineFormData.delete(
                            "ticket_image"
                        );

                        const action =
                            form.action;

                        const method = (
                            form.method ||
                            "POST"
                        ).toUpperCase();

                        console.log(
                            "Online ticket submission URL:",
                            action
                        );

                        console.log(
                            "Online ticket submission method:",
                            method
                        );

                        // ===================================================
                        // SUBMIT USING FETCH
                        // with a timeout so a dead connection
                        // does not hang forever
                        // ===================================================

                        const controller =
                            new AbortController();

                        const timeoutId =
                            setTimeout(
                                function () {
                                    controller.abort();
                                },
                                30000
                            );

                        let response;

                        try {
                            response =
                                await fetch(
                                    action,
                                    {
                                        method:
                                            method,
                                        body:
                                            onlineFormData,
                                        credentials:
                                            "same-origin",
                                        signal:
                                            controller.signal,
                                        headers: {
                                            Accept:
                                                "application/json",
                                            "X-Requested-With":
                                                "XMLHttpRequest",
                                        },
                                    }
                                );
                        } finally {
                            clearTimeout(
                                timeoutId
                            );
                        }

                        console.log(
                            "Online ticket response status:",
                            response.status
                        );

                        // ===================================================
                        // HANDLE SERVER ERROR
                        // ===================================================

                        if (!response.ok) {
                            let message =
                                `Server returned HTTP ${response.status}.`;

                            try {
                                const data =
                                    await response.json();

                                if (data.message) {
                                    message =
                                        data.message;
                                }

                                if (data.errors) {
                                    const firstError =
                                        Object.values(
                                            data.errors
                                        )
                                            .flat()
                                            .find(
                                                Boolean
                                            );

                                    if (firstError) {
                                        message =
                                            firstError;
                                    }
                                }
                            } catch (error) {
                                console.error(
                                    "Unable to read server error response:",
                                    error
                                );
                            }

                            throw new Error(
                                message
                            );
                        }

                        // ===================================================
                        // TRY TO READ JSON RESPONSE
                        // ===================================================

                        let responseData =
                            null;

                        try {
                            responseData =
                                await response.json();
                        } catch (error) {
                            console.log(
                                "Server response was not JSON."
                            );
                        }

                        console.log(
                            "Online citation ticket submitted successfully."
                        );

                        // ===================================================
                        // OCR FILES ARE NO LONGER NEEDED.
                        // Clear them immediately after successful submission.
                        // ===================================================

                        clearAllTemporaryOcrInputs();

                        // ===================================================
                        // IF LARAVEL RETURNS A REDIRECT URL
                        // ===================================================

                        if (
                            responseData &&
                            responseData.redirect
                        ) {
                            window.location.href =
                                responseData.redirect;

                            return;
                        }

                        // ===================================================
                        // IF THE RESPONSE URL CHANGED BECAUSE LARAVEL
                        // REDIRECTED AFTER SUCCESSFUL SUBMISSION
                        // ===================================================

                        if (
                            response.url &&
                            response.url !==
                                window.location.href
                        ) {
                            window.location.href =
                                response.url;

                            return;
                        }

                        // ===================================================
                        // FALLBACK SUCCESS
                        // ===================================================

                        window.location.reload();
                    } catch (error) {
                        console.error(
                            "Online ticket submission error:",
                            error
                        );

                        // ===================================================
                        // NETWORK FAILURE
                        //
                        // Browser says online but the server is unreachable
                        // or request timed out.
                        //
                        // OCR files are cleared before offline storage.
                        // ===================================================

                        const isNetworkFailure =
                            error &&
                            (
                                error.name ===
                                    "TypeError" ||
                                error.name ===
                                    "AbortError" ||
                                !navigator.onLine
                            );

                        if (
                            isNetworkFailure
                        ) {
                            await saveTicketForLater(
                                true
                            );

                            return;
                        }

                        showTicketProcessingError(
                            error.message ||
                                "Unable to submit the citation ticket. Please try again."
                        );
                    }

                    return;
                }

                // ===================================================
                // OFFLINE
                // ===================================================

                event.preventDefault();

                // ===================================================
                // PREVENT MULTIPLE OFFLINE SAVES
                // ===================================================

                if (syncInProgress) {
                    return;
                }

                await saveTicketForLater(
                    false
                );
            }
        );
    }

    // ===================================================
    // INTERNET CONNECTION RESTORED
    // ===================================================

    window.addEventListener(
        "online",
        function () {
            console.log(
                "Internet connection restored."
            );

            updateConnectionStatus();

            setTimeout(function () {
                syncPendingTickets();
            }, 1500);
        }
    );

    // ===================================================
    // INTERNET CONNECTION LOST
    // ===================================================

    window.addEventListener(
        "offline",
        function () {
            console.log(
                "Internet connection lost."
            );

            updateConnectionStatus();

            updatePendingSyncCount();
        }
    );

    // ===================================================
    // AUTO-RETRY SYNC
    //
    // The "online" event can fire before the connection is
    // really usable, or be missed. Retry while tickets wait.
    // ===================================================

    setInterval(
        async function () {
            if (
                !navigator.onLine ||
                syncInProgress
            ) {
                return;
            }

            try {
                const tickets =
                    await getPendingTickets();

                if (tickets.length > 0) {
                    console.log(
                        "Auto-retry: pending tickets found, syncing..."
                    );

                    syncPendingTickets();
                }
            } catch (error) {
                console.warn(
                    "Auto-retry check failed:",
                    error
                );
            }
        },
        20000
    );

    document.addEventListener(
        "visibilitychange",
        function () {
            if (
                document.visibilityState ===
                    "visible" &&
                navigator.onLine
            ) {
                syncPendingTickets();
            }
        }
    );

    // ===================================================
    // INITIALIZE OFFLINE STORAGE
    // ===================================================

    openOfflineDatabase()
        .then(function (db) {
            console.log(
                "TrafficEnforceNet IndexedDB is ready."
            );

            console.log(
                "Database:",
                db.name
            );

            console.log(
                "Object stores:",
                Array.from(
                    db.objectStoreNames
                )
            );

            db.close();
        })
        .catch(function (error) {
            console.error(
                "Unable to initialize IndexedDB:",
                error
            );
        });

    // ===================================================
    // INITIALIZE PENDING COUNT
    // ===================================================

    updatePendingSyncCount();

    // ===================================================
    // TRY SYNCHRONIZATION WHEN PAGE LOADS ONLINE
    // ===================================================

    if (navigator.onLine) {
        console.log(
            "Page loaded while online. Checking pending tickets..."
        );

        setTimeout(function () {
            syncPendingTickets();
        }, 1000);
    }

    // ===================================================
    // GPS ELEMENTS
    // ===================================================

    const locationInput =
        getElement("location");

    const latitudeInput =
        getElement("latitude");

    const longitudeInput =
        getElement("longitude");

    const gpsBadge =
        getElement("gpsStatusBadge");

    const gpsCoordinates =
        getElement("gpsCoordinates");

    const locationIcon =
        getElement("locationIcon");

    // ===================================================
    // CHECK GPS SUPPORT
    // ===================================================

    if (
        !navigator.geolocation ||
        !latitudeInput ||
        !longitudeInput
    ) {
        if (gpsBadge) {
            gpsBadge.innerText =
                "Unavailable";

            gpsBadge.classList.remove(
                "bg-yellow-50",
                "text-yellow-700"
            );

            gpsBadge.classList.add(
                "bg-red-50",
                "text-red-700"
            );
        }

        if (locationInput) {
            locationInput.value =
                "Location services are not available on this device";
        }

        if (locationIcon) {
            locationIcon.innerText = "⚠️";
        }

        return;
    }

    // ===================================================
    // INITIAL GPS STATUS
    // ===================================================

    if (gpsBadge) {
        gpsBadge.innerText =
            "Detecting";

        gpsBadge.classList.remove(
            "bg-blue-50",
            "bg-green-50",
            "bg-red-50",
            "text-blue-700",
            "text-green-700",
            "text-red-700"
        );

        gpsBadge.classList.add(
            "bg-yellow-50",
            "text-yellow-700"
        );
    }

    if (locationInput) {
        locationInput.value =
            "Getting accurate GPS location...";
    }

    if (locationIcon) {
        locationIcon.innerText = "⏳";
    }

    if (gpsCoordinates) {
        gpsCoordinates.classList.remove(
            "hidden"
        );

        gpsCoordinates.innerHTML = `
            <p class="text-xs text-blue-700">
                ⏳ Searching for the most accurate GPS reading...
            </p>
            <p class="text-[11px] text-blue-500 mt-1">
                Please keep the device outdoors or near a clear view of the sky.
            </p>
        `;
    }

    // ===================================================
    // HIGH-ACCURACY GPS SETTINGS
    // ===================================================

    const TARGET_ACCURACY = 10;
    const MAX_GPS_TIME = 20000;

    let bestPosition = null;
    let bestAccuracy = Infinity;
    let gpsWatchId = null;
    let gpsFinished = false;

    // ===================================================
    // FINISH GPS CAPTURE
    // ===================================================

    function finishGPS(position) {
        if (gpsFinished) {
            return;
        }

        gpsFinished = true;

        if (gpsWatchId !== null) {
            navigator.geolocation.clearWatch(
                gpsWatchId
            );

            gpsWatchId = null;
        }

        if (!position) {
            if (gpsBadge) {
                gpsBadge.innerText =
                    "Unavailable";

                gpsBadge.classList.remove(
                    "bg-yellow-50",
                    "text-yellow-700"
                );

                gpsBadge.classList.add(
                    "bg-red-50",
                    "text-red-700"
                );
            }

            if (locationInput) {
                locationInput.value =
                    "Unable to detect current location";
            }

            if (locationIcon) {
                locationIcon.innerText = "⚠️";
            }

            if (gpsCoordinates) {
                gpsCoordinates.innerHTML = `
                    <p class="text-xs text-red-700">
                        ⚠️ Unable to obtain GPS coordinates.
                    </p>
                    <p class="text-[11px] text-red-500 mt-1">
                        Check that Location Services and browser location permission are enabled.
                    </p>
                `;
            }

            return;
        }

        const latitude =
            position.coords.latitude;

        const longitude =
            position.coords.longitude;

        const accuracy =
            position.coords.accuracy;

        console.log(
            "================================="
        );

        console.log(
            "FINAL GPS LOCATION"
        );

        console.log(
            "Latitude:",
            latitude
        );

        console.log(
            "Longitude:",
            longitude
        );

        console.log(
            "Accuracy:",
            accuracy,
            "meters"
        );

        console.log(
            "================================="
        );

        latitudeInput.value =
            latitude;

        longitudeInput.value =
            longitude;

        if (gpsBadge) {
            gpsBadge.innerText =
                `Captured ±${Math.round(
                    accuracy
                )}m`;

            gpsBadge.classList.remove(
                "bg-yellow-50",
                "text-yellow-700",
                "bg-red-50",
                "text-red-700"
            );

            gpsBadge.classList.add(
                "bg-green-50",
                "text-green-700"
            );
        }

        if (gpsCoordinates) {
            gpsCoordinates.classList.remove(
                "hidden"
            );

            gpsCoordinates.innerHTML = `
                <p class="text-xs text-blue-700">
                    ✓ GPS coordinates captured successfully.
                </p>
                <p class="text-[11px] text-blue-500 mt-1">
                    Accuracy: approximately ${Math.round(
                        accuracy
                    )} meters
                </p>
            `;
        }

        if (locationInput) {
            locationInput.value =
                "Detecting address...";
        }

        if (locationIcon) {
            locationIcon.innerText = "⏳";
        }

        // ===================================================
        // REVERSE GEOCODING
        // ===================================================

        const reverseGeocodeUrl =
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${encodeURIComponent(
                latitude
            )}&lon=${encodeURIComponent(
                longitude
            )}&zoom=18&addressdetails=1`;

        fetch(reverseGeocodeUrl, {
            headers: {
                "Accept-Language": "en",
            },
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error(
                        `HTTP ${response.status}`
                    );
                }

                return response.json();
            })
            .then(function (data) {
                if (
                    data &&
                    data.address
                ) {
                    const address =
                        data.address;

                    const road =
                        address.road ||
                        address.pedestrian ||
                        address.highway ||
                        "";

                    const barangay =
                        address.suburb ||
                        address.village ||
                        address.quarter ||
                        address.neighbourhood ||
                        "";

                    const city =
                        address.city ||
                        address.town ||
                        address.municipality ||
                        "";

                    const province =
                        address.state || "";

                    const formattedAddress = [
                        road,
                        barangay,
                        city,
                        province,
                    ]
                        .filter(Boolean)
                        .join(", ");

                    if (locationInput) {
                        locationInput.value =
                            formattedAddress ||
                            "Address unavailable";
                    }

                    if (locationIcon) {
                        locationIcon.innerText =
                            "✓";
                    }

                    console.log(
                        "GPS Address:",
                        data.display_name
                    );

                    console.log(
                        "Road:",
                        road
                    );

                    console.log(
                        "Barangay:",
                        barangay
                    );

                    console.log(
                        "City/Municipality:",
                        city
                    );

                    console.log(
                        "Province:",
                        province
                    );
                } else {
                    if (locationInput) {
                        locationInput.value =
                            "Address unavailable";
                    }

                    if (locationIcon) {
                        locationIcon.innerText =
                            "⚠️";
                    }
                }
            })
            .catch(function (error) {
                console.error(
                    "Reverse geocoding error:",
                    error
                );

                if (locationInput) {
                    locationInput.value =
                        "Unable to get address";
                }

                if (locationIcon) {
                    locationIcon.innerText =
                        "⚠️";
                }
            });
    }

    // ===================================================
    // GPS ERROR HANDLER
    // ===================================================

    function handleGPSError(error) {
        console.error(
            "GPS Error:",
            error.code,
            error.message
        );

        if (gpsFinished) {
            return;
        }

        if (bestPosition) {
            finishGPS(bestPosition);
            return;
        }

        if (error.code === 1) {
            finishGPS(null);

            if (gpsCoordinates) {
                gpsCoordinates.innerHTML = `
                    <p class="text-xs text-red-700">
                        ⚠️ Location permission was denied.
                    </p>
                    <p class="text-[11px] text-red-500 mt-1">
                        Please allow location access in the browser and try again.
                    </p>
                `;
            }

            if (locationInput) {
                locationInput.value =
                    "Location permission denied";
            }

            return;
        }

        if (error.code === 2) {
            finishGPS(null);

            if (gpsCoordinates) {
                gpsCoordinates.innerHTML = `
                    <p class="text-xs text-red-700">
                        ⚠️ GPS signal is unavailable.
                    </p>
                    <p class="text-[11px] text-red-500 mt-1">
                        Try moving outdoors or to an area with a clearer view of the sky.
                    </p>
                `;
            }

            if (locationInput) {
                locationInput.value =
                    "GPS signal unavailable";
            }

            return;
        }

        if (error.code === 3) {
            console.log(
                "GPS reading timed out. Waiting for the maximum GPS capture period."
            );

            if (gpsBadge) {
                gpsBadge.innerText =
                    "Searching";
            }

            if (gpsCoordinates) {
                gpsCoordinates.classList.remove(
                    "hidden"
                );

                gpsCoordinates.innerHTML = `
                    <p class="text-xs text-blue-700">
                        ⏳ GPS is still being checked...
                    </p>
                    <p class="text-[11px] text-blue-500 mt-1">
                        Waiting for the best available location reading.
                    </p>
                `;
            }

            return;
        }

        finishGPS(null);
    }

    // ===================================================
    // START HIGH-ACCURACY GPS WATCH
    // ===================================================

    gpsWatchId =
        navigator.geolocation.watchPosition(
            function (position) {
                if (gpsFinished) {
                    return;
                }

                const accuracy =
                    position.coords.accuracy;

                console.log(
                    "GPS Reading:",
                    position.coords.latitude,
                    position.coords.longitude,
                    "Accuracy:",
                    accuracy,
                    "meters"
                );

                if (
                    !bestPosition ||
                    accuracy < bestAccuracy
                ) {
                    bestPosition =
                        position;

                    bestAccuracy =
                        accuracy;

                    console.log(
                        "New best GPS accuracy:",
                        bestAccuracy,
                        "meters"
                    );

                    if (gpsBadge) {
                        gpsBadge.innerText =
                            `Improving ±${Math.round(
                                accuracy
                            )}m`;

                        gpsBadge.classList.remove(
                            "bg-green-50",
                            "text-green-700",
                            "bg-red-50",
                            "text-red-700"
                        );

                        gpsBadge.classList.add(
                            "bg-yellow-50",
                            "text-yellow-700"
                        );
                    }

                    if (gpsCoordinates) {
                        gpsCoordinates.classList.remove(
                            "hidden"
                        );

                        gpsCoordinates.innerHTML = `
                            <p class="text-xs text-blue-700">
                                ⏳ Improving GPS accuracy...
                            </p>
                            <p class="text-[11px] text-blue-500 mt-1">
                                Current accuracy: approximately ${Math.round(
                                    accuracy
                                )} meters
                            </p>
                        `;
                    }
                }

                if (
                    accuracy <=
                    TARGET_ACCURACY
                ) {
                    console.log(
                        "Target GPS accuracy reached:",
                        accuracy,
                        "meters"
                    );

                    finishGPS(
                        bestPosition
                    );
                }
            },
            function (error) {
                handleGPSError(error);
            },
            {
                enableHighAccuracy: true,
                timeout: MAX_GPS_TIME,
                maximumAge: 0,
            }
        );

    // ===================================================
    // GPS MAXIMUM WAIT TIMER
    // ===================================================

    setTimeout(function () {
        if (gpsFinished) {
            return;
        }

        console.log(
            "GPS maximum wait time reached."
        );

        if (bestPosition) {
            console.log(
                "Using best available GPS accuracy:",
                bestAccuracy,
                "meters"
            );

            finishGPS(
                bestPosition
            );
        } else {
            console.log(
                "No GPS reading was obtained."
            );

            finishGPS(null);
        }
    }, MAX_GPS_TIME);
});