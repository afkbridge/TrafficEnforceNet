// =======================================================
// TRAFFICENFORCENET
// ENFORCER - ISSUE TICKET JAVASCRIPT
// =======================================================

document.addEventListener("DOMContentLoaded", function () {
    // ===================================================
    // HELPER - GET ELEMENT
    // ===================================================

    function getElement(id) {
        return document.getElementById(id);
    }

    // ===================================================
    // HELPER - ONLY SET VALUE IF OCR FOUND SOMETHING
    // ===================================================

    function setFieldValue(id, value) {
        const field = getElement(id);

        if (
            field &&
            value !== null &&
            value !== undefined &&
            String(value).trim() !== ""
        ) {
            field.value = String(value).trim();
        }
    }

    // ===================================================
    // DRIVER'S LICENSE OCR STATUS
    // ===================================================

    function setDriverOcrStatus(type, title, message) {
        const badge = getElement("ocrBadge");
        const status = getElement("ocrStatus");
        const icon = getElement("ocrIcon");
        const ocrTitle = getElement("ocrTitle");
        const ocrMessage = getElement("ocrMessage");

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
    // CITATION TICKET OCR STATUS
    // ===================================================

    function setTicketOcrStatus(type, title, message) {
        const badge = getElement("ticketOcrBadge");
        const status = getElement("ticketOcrStatus");
        const icon = getElement("ticketOcrIcon");
        const ocrTitle = getElement("ticketOcrTitle");
        const ocrMessage = getElement("ticketOcrMessage");

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
    // DRIVER'S LICENSE CAMERA
    // ===================================================

    window.openLicenseCamera = function () {
        const input = getElement("driver_license");

        if (!input) {
            console.error("Driver license input not found.");
            return;
        }

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

        // -----------------------------------------------
        // PREVIEW
        // -----------------------------------------------

        const preview = getElement("licensePreview");
        const container = getElement("licensePreviewContainer");

        if (preview && container) {
            if (preview.src) {
                URL.revokeObjectURL(preview.src);
            }

            preview.src = URL.createObjectURL(file);
            container.classList.remove("hidden");
        }

        // -----------------------------------------------
        // STATUS
        // -----------------------------------------------

        setDriverOcrStatus(
            "loading",
            "Reading driver's license...",
            "Please wait while the system extracts the driver information."
        );

        // -----------------------------------------------
        // FORM DATA
        // -----------------------------------------------

        const formData = new FormData();
        formData.append("driver_license", file);

        try {
            // -------------------------------------------
            // SEND TO LARAVEL
            // -------------------------------------------

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
            });

            // -------------------------------------------
            // READ RESPONSE
            // -------------------------------------------

            const data = await response.json();

            // -------------------------------------------
            // ERROR RESPONSE
            // -------------------------------------------

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message || "Driver's license OCR failed."
                );
            }

            // -------------------------------------------
            // OCR DATA
            // -------------------------------------------

            const result = data.data || {};

            // -------------------------------------------
            // POPULATE DRIVER FIELDS
            // -------------------------------------------

            setFieldValue("first_name", result.first_name);
            setFieldValue("middle_name", result.middle_name);
            setFieldValue("last_name", result.last_name);
            setFieldValue("license_number", result.license_number);
            setFieldValue("address", result.address);
            setFieldValue("birth_date", result.birth_date);

            // -------------------------------------------
            // SUCCESS
            // -------------------------------------------

            setDriverOcrStatus(
                "success",
                "Driver's license scanned",
                data.message ||
                    "Driver information was extracted successfully."
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

        // -----------------------------------------------
        // PREVIEW
        // -----------------------------------------------

        const preview = getElement("ticketPreview");
        const container = getElement("ticketPreviewContainer");

        if (preview && container) {
            if (preview.src) {
                URL.revokeObjectURL(preview.src);
            }

            preview.src = URL.createObjectURL(file);
            container.classList.remove("hidden");
        }

        // -----------------------------------------------
        // START OCR
        // -----------------------------------------------

        processCitationTicket(file);
    };

    // ===================================================
    // CITATION TICKET OCR
    // ===================================================

    async function processCitationTicket(file) {
        if (!file) {
            return;
        }

        // -----------------------------------------------
        // STATUS
        // -----------------------------------------------

        setTicketOcrStatus(
            "loading",
            "Reading citation ticket...",
            "Please wait while the system extracts the ticket information."
        );

        // -----------------------------------------------
        // FORM DATA
        // -----------------------------------------------

        const formData = new FormData();
        formData.append("ticket_image", file);

        try {
            // -------------------------------------------
            // SEND TO LARAVEL
            // -------------------------------------------

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
            });

            // -------------------------------------------
            // READ RESPONSE
            // -------------------------------------------

            const data = await response.json();

            // -------------------------------------------
            // ERROR RESPONSE
            // -------------------------------------------

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message || "Citation ticket OCR failed."
                );
            }

            // -------------------------------------------
            // OCR RESULT
            // -------------------------------------------

            const result = data.data || {};

            // -------------------------------------------
            // POPULATE TICKET NUMBER
            // -------------------------------------------

            setFieldValue("ticket_number", result.ticket_number);

            // -------------------------------------------
            // POPULATE DRIVER INFORMATION
            // -------------------------------------------

            setFieldValue("first_name", result.first_name);
            setFieldValue("middle_name", result.middle_name);
            setFieldValue("last_name", result.last_name);
            setFieldValue("license_number", result.license_number);
            setFieldValue("address", result.address);
            setFieldValue("birth_date", result.birth_date);

            // -------------------------------------------
            // POPULATE VEHICLE INFORMATION
            // -------------------------------------------

            setFieldValue("plate_number", result.plate_number);
            setFieldValue("vehicle_type", result.vehicle_type);
            setFieldValue("region_number", result.region_number);
            setFieldValue("owner_name", result.owner_name);

            // -------------------------------------------
            // IMPORTANT:
            // DO NOT POPULATE GPS LOCATION FROM OCR
            //
            // The location field is controlled by GPS.
            // The ticket's "Place of Violation" should
            // not overwrite the actual captured GPS address.
            // -------------------------------------------

            // -------------------------------------------
            // HANDLE DETECTED VIOLATIONS
            // -------------------------------------------

            handleDetectedViolations(result.violations || []);

            // -------------------------------------------
            // SUCCESS
            // -------------------------------------------

            setTicketOcrStatus(
                "success",
                "Citation ticket scanned",
                data.message ||
                    "Ticket information was extracted successfully."
            );

            console.log("Citation Ticket OCR:", result);

            // -------------------------------------------
            // RAW OCR TEXT
            // -------------------------------------------

            if (data.raw_text) {
                console.log(
                    "Citation Ticket OCR Raw Text:",
                    data.raw_text
                );
            }
        } catch (error) {
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

        // -----------------------------------------------
        // NORMALIZE VIOLATIONS
        // -----------------------------------------------

        const detected = violations
            .map(function (violation) {
                return String(violation)
                    .replace(/\s+/g, " ")
                    .trim();
            })
            .filter(Boolean);

        if (detected.length === 0) {
            return;
        }

        // -----------------------------------------------
        // TRY TO MATCH EXISTING VIOLATION TYPE
        // -----------------------------------------------

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

        // -----------------------------------------------
        // SELECT FIRST MATCH
        // -----------------------------------------------

        if (matchedOptions.length > 0) {
            select.value = matchedOptions[0].value;

            if (otherContainer) {
                otherContainer.classList.add("hidden");
            }
        }

        // -----------------------------------------------
        // IF NO MATCH, USE OTHER
        // -----------------------------------------------

        else {
            const otherOption = options.find(function (option) {
                return option.value === "other";
            });

            if (otherOption) {
                select.value = "other";

                if (otherContainer) {
                    otherContainer.classList.remove("hidden");
                }

                if (otherInput) {
                    otherInput.value = detected.join("; ");
                }
            }
        }

        // -----------------------------------------------
        // PUT DETECTED VIOLATIONS IN REMARKS
        // -----------------------------------------------

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
    const otherContainer = getElement("otherViolationContainer");

    if (violationSelect && otherContainer) {
        violationSelect.addEventListener("change", function () {
            if (this.value === "other") {
                otherContainer.classList.remove("hidden");
            } else {
                otherContainer.classList.add("hidden");
            }
        });
    }

    // ===================================================
    // OFFLINE SYNC
    // ===================================================

    const form = getElement("issueTicketForm");

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

    const OFFLINE_DB_NAME = "TrafficEnforceNetDB";
    const OFFLINE_DB_VERSION = 1;
    const OFFLINE_STORE_NAME = "pendingTickets";

    let syncInProgress = false;

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

                if (!db.objectStoreNames.contains(OFFLINE_STORE_NAME)) {
                    db.createObjectStore(OFFLINE_STORE_NAME, {
                        keyPath: "id",
                        autoIncrement: true,
                    });
                }
            };

            request.onsuccess = function () {
                resolve(request.result);
            };

            request.onerror = function () {
                reject(
                    request.error ||
                        new Error("Unable to open offline storage.")
                );
            };
        });
    }

    // ===================================================
    // SAVE TICKET OFFLINE
    // ===================================================

    async function saveTicketOffline() {
        if (!form) {
            throw new Error("Issue ticket form was not found.");
        }

        const formData = new FormData(form);
        const fields = {};
        const files = {};

        // -----------------------------------------------
        // STORE FORM DATA
        // -----------------------------------------------

        for (const [name, value] of formData.entries()) {
            if (value instanceof File || value instanceof Blob) {
                if (!value.size) {
                    continue;
                }

                const fileData = {
                    name: value.name || `${name}.file`,
                    type: value.type || "application/octet-stream",
                    lastModified: value.lastModified || Date.now(),
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
                        fields[name] = [fields[name]];
                    }

                    fields[name].push(String(value));
                }
            }
        }

        // -----------------------------------------------
        // SAVE TO INDEXEDDB
        // -----------------------------------------------

        const db = await openOfflineDatabase();

        return new Promise(function (resolve, reject) {
            const transaction = db.transaction(
                OFFLINE_STORE_NAME,
                "readwrite"
            );

            const store = transaction.objectStore(OFFLINE_STORE_NAME);

            const request = store.add({
                fields: fields,
                files: files,
                action: form.action,
                createdAt: new Date().toISOString(),
            });

            request.onsuccess = function () {
                resolve(request.result);
            };

            request.onerror = function () {
                reject(
                    request.error ||
                        new Error("Unable to save ticket offline.")
                );
            };
        });
    }

    // ===================================================
    // GET ALL PENDING TICKETS
    // ===================================================

    async function getPendingTickets() {
        const db = await openOfflineDatabase();

        return new Promise(function (resolve, reject) {
            const transaction = db.transaction(
                OFFLINE_STORE_NAME,
                "readonly"
            );

            const store = transaction.objectStore(OFFLINE_STORE_NAME);
            const request = store.getAll();

            request.onsuccess = function () {
                resolve(request.result || []);
            };

            request.onerror = function () {
                reject(
                    request.error ||
                        new Error("Unable to read pending tickets.")
                );
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

            const store = transaction.objectStore(OFFLINE_STORE_NAME);
            const request = store.delete(id);

            request.onsuccess = function () {
                resolve();
            };

            request.onerror = function () {
                reject(
                    request.error ||
                        new Error("Unable to remove synced ticket.")
                );
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
            const token = meta.getAttribute("content");

            if (token) {
                return token;
            }
        }

        const tokenInput = form?.querySelector(
            'input[name="_token"]'
        );

        return tokenInput?.value || "";
    }

    // ===================================================
    // BUILD FORM DATA FOR SYNC
    // ===================================================

    function buildSyncFormData(ticket) {
        const syncFormData = new FormData();

        // -----------------------------------------------
        // FORM FIELDS
        // -----------------------------------------------

        Object.entries(ticket.fields || {}).forEach(
            function ([name, value]) {
                if (Array.isArray(value)) {
                    value.forEach(function (item) {
                        syncFormData.append(name, item);
                    });
                } else {
                    if (name !== "_token") {
                        syncFormData.append(name, value);
                    }
                }
            }
        );

        // -----------------------------------------------
        // USE CURRENT CSRF TOKEN
        // -----------------------------------------------

        const csrfToken = getCsrfToken();

        if (csrfToken) {
            syncFormData.append("_token", csrfToken);
        }

        // -----------------------------------------------
        // FILES
        // -----------------------------------------------

        Object.entries(ticket.files || {}).forEach(
            function ([name, fileData]) {
                if (Array.isArray(fileData)) {
                    fileData.forEach(function (item) {
                        const file = new File(
                            [item.blob],
                            item.name,
                            {
                                type: item.type,
                                lastModified: item.lastModified,
                            }
                        );

                        syncFormData.append(name, file);
                    });
                } else {
                    const file = new File(
                        [fileData.blob],
                        fileData.name,
                        {
                            type: fileData.type,
                            lastModified: fileData.lastModified,
                        }
                    );

                    syncFormData.append(name, file);
                }
            }
        );

        return syncFormData;
    }

    // ===================================================
    // SHOW / HIDE OFFLINE STATUS
    // ===================================================

    function hideOfflineStatus() {
        if (offlinePendingBox) {
            offlinePendingBox.classList.add("hidden");
        }

        if (offlineSyncingBox) {
            offlineSyncingBox.classList.add("hidden");
        }

        if (offlineSuccessBox) {
            offlineSuccessBox.classList.add("hidden");
        }

        if (offlineErrorBox) {
            offlineErrorBox.classList.add("hidden");
        }
    }

    // ===================================================
    // SHOW PENDING TICKETS
    // ===================================================

    function showPendingTickets(count) {
        if (!offlinePendingBox) {
            return;
        }

        offlinePendingBox.classList.remove("hidden");

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
                syncNowButton.classList.remove("hidden");
                syncNowButton.disabled = false;
                syncNowButton.innerText = "Sync Now";
            } else {
                syncNowButton.classList.add("hidden");
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
            offlineSyncingBox.classList.remove("hidden");
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
            offlineSuccessBox.classList.remove("hidden");
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
    // SHOW SYNC ERROR
    // ===================================================

    function showSyncError(message) {
        hideOfflineStatus();

        if (offlineErrorBox) {
            offlineErrorBox.classList.remove("hidden");
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
            const tickets = await getPendingTickets();
            const count = tickets.length;

            if (count === 0) {
                hideOfflineStatus();
                return;
            }

            showPendingTickets(count);
        } catch (error) {
            console.error("Offline storage error:", error);

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

        const syncFormData = buildSyncFormData(ticket);

        const response = await fetch(
            ticket.action || form.action,
            {
                method: "POST",
                body: syncFormData,
                credentials: "same-origin",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            }
        );

        // -----------------------------------------------
        // SERVER VALIDATION / ERROR
        // -----------------------------------------------

        if (!response.ok) {
            let message =
                `Server returned HTTP ${response.status}.`;

            try {
                const data = await response.json();

                if (data.message) {
                    message = data.message;
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
            const tickets = await getPendingTickets();

            if (tickets.length === 0) {
                hideOfflineStatus();
                return;
            }

            showSyncing(tickets.length);

            let syncedCount = 0;
            let failedCount = 0;
            let lastError = "";

            for (const ticket of tickets) {
                if (!navigator.onLine) {
                    failedCount += tickets.length - syncedCount;

                    lastError =
                        "Internet connection was lost during synchronization.";

                    break;
                }

                try {
                    await syncOneTicket(ticket);

                    // -----------------------------------
                    // IMPORTANT:
                    // DELETE ONLY AFTER SUCCESS
                    // -----------------------------------

                    await deletePendingTicket(ticket.id);

                    syncedCount++;
                } catch (error) {
                    console.error(
                        `Failed to sync ticket ${ticket.id}:`,
                        error
                    );

                    failedCount++;

                    lastError =
                        error.message ||
                        "Unable to synchronize ticket.";
                }
            }

            // -------------------------------------------
            // REFRESH PENDING TICKETS
            // -------------------------------------------

            const remainingTickets =
                await getPendingTickets();

            const remainingCount =
                remainingTickets.length;

            // -------------------------------------------
            // EVERYTHING SYNCED
            // -------------------------------------------

            if (remainingCount === 0) {
                if (syncedCount > 0) {
                    showSyncSuccess(syncedCount);
                } else {
                    hideOfflineStatus();
                }

                return;
            }

            // -------------------------------------------
            // SOME FAILED
            // -------------------------------------------

            showSyncError(
                `${remainingCount} ticket${
                    remainingCount === 1 ? "" : "s"
                } remain pending. ${
                    lastError ||
                    "Please try again when the connection is stable."
                }`
            );

            // Show pending information again
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
    // INTERCEPT FORM SUBMISSION WHEN OFFLINE
    // ===================================================

    if (form) {
        form.addEventListener(
            "submit",
            async function (event) {
                // ---------------------------------------
                // ONLINE
                // ---------------------------------------
                //
                // Allow the browser to submit the form
                // normally to Laravel.
                //
                // Laravel will then redirect to:
                // enforcer.success
                //

                if (navigator.onLine) {
                    return true;
                }

                // ---------------------------------------
                // OFFLINE
                // ---------------------------------------
                //
                // Prevent the normal Laravel submission
                // and save the ticket locally instead.
                //

                event.preventDefault();

                if (syncInProgress) {
                    return;
                }

                try {
                    // -----------------------------------
                    // SAVE LOCALLY
                    // -----------------------------------

                    await saveTicketOffline();

                    // -----------------------------------
                    // UPDATE STATUS
                    // -----------------------------------

                    await updatePendingSyncCount();

                    // -----------------------------------
                    // INFORM USER
                    // -----------------------------------

                    if (offlinePendingMessage) {
                        const tickets =
                            await getPendingTickets();

                        const count = tickets.length;

                        offlinePendingMessage.innerText =
                            `You are offline. Your ticket has been saved on this device. ${count} ticket${
                                count === 1 ? "" : "s"
                            } waiting to sync.`;
                    }

                    console.log(
                        "Ticket saved successfully for offline synchronization."
                    );
                } catch (error) {
                    console.error(
                        "Offline ticket save error:",
                        error
                    );

                    showSyncError(
                        error.message ||
                            "Unable to save the ticket on this device."
                    );
                }
            }
        );
    }

    // ===================================================
    // CONNECTION RESTORED
    // ===================================================

    window.addEventListener("online", function () {
        console.log("Internet connection restored.");

        setTimeout(function () {
            syncPendingTickets();
        }, 1500);
    });

    // ===================================================
    // CONNECTION LOST
    // ===================================================

    window.addEventListener("offline", function () {
        console.log("Internet connection lost.");

        updatePendingSyncCount();
    });

    // ===================================================
    // INITIALIZE OFFLINE SYNC
    // ===================================================

    updatePendingSyncCount();

    // ===================================================
    // GPS ELEMENTS
    // ===================================================

    const locationInput = getElement("location");
    const latitudeInput = getElement("latitude");
    const longitudeInput = getElement("longitude");
    const gpsBadge = getElement("gpsStatusBadge");
    const gpsCoordinates = getElement("gpsCoordinates");
    const locationIcon = getElement("locationIcon");

    // ===================================================
    // CHECK GPS SUPPORT
    // ===================================================

    if (
        !navigator.geolocation ||
        !latitudeInput ||
        !longitudeInput
    ) {
        if (gpsBadge) {
            gpsBadge.innerText = "Unavailable";

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
                "GPS is not supported on this device";
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
        gpsBadge.innerText = "Detecting";
    }

    if (locationInput) {
        locationInput.value =
            "Detecting current location...";
    }

    if (locationIcon) {
        locationIcon.innerText = "⏳";
    }

    // ===================================================
    // GET HIGH-ACCURACY GPS
    // ===================================================

    navigator.geolocation.getCurrentPosition(
        // =================================================
        // GPS SUCCESS
        // =================================================

        function (position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const accuracy = position.coords.accuracy;

            // =================================================
            // GPS DEBUG
            // =================================================

            console.log("GPS Latitude:", latitude);
            console.log("GPS Longitude:", longitude);
            console.log(
                "GPS Accuracy:",
                accuracy,
                "meters"
            );

            // =================================================
            // SAVE COORDINATES
            // =================================================

            latitudeInput.value = latitude;
            longitudeInput.value = longitude;

            // =================================================
            // GPS BADGE
            // =================================================

            if (gpsBadge) {
                gpsBadge.innerText =
                    `Captured ±${Math.round(accuracy)}m`;

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

            // =================================================
            // GPS COORDINATE INFORMATION
            // =================================================

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

            // =================================================
            // PREPARE ADDRESS DETECTION
            // =================================================

            if (locationInput) {
                locationInput.value =
                    "Detecting address...";
            }

            if (locationIcon) {
                locationIcon.innerText = "⏳";
            }

            // =================================================
            // REVERSE GEOCODING
            // =================================================

            const reverseGeocodeUrl =
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`;

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
                    if (data && data.address) {
                        const address = data.address;

                        // =================================================
                        // ADDRESS COMPONENTS
                        // =================================================

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

                        // =================================================
                        // FORMAT ADDRESS
                        // =================================================

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
                            locationIcon.innerText = "✓";
                        }

                        // =================================================
                        // DEBUG ADDRESS DATA
                        // =================================================

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
                            locationIcon.innerText = "⚠️";
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
                        locationIcon.innerText = "⚠️";
                    }
                });
        },

        // =================================================
        // GPS ERROR
        // =================================================

        function (error) {
            console.error(
                "GPS Error:",
                error.code,
                error.message
            );

            if (gpsBadge) {
                gpsBadge.innerText = "Unavailable";

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
        },

        // =================================================
        // GPS OPTIONS
        // =================================================

        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0,
        }
    );
});