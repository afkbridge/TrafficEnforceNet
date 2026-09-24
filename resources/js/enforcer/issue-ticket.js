// =======================================================
// TRAFFICENFORCENET
// ENFORCER - ISSUE TICKET JAVASCRIPT
// =======================================================

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

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
                badge.classList.add(
                    "bg-green-100",
                    "text-green-700"
                );
                badge.innerText = "Completed";
            } else if (type === "error") {
                badge.classList.add(
                    "bg-red-100",
                    "text-red-700"
                );
                badge.innerText = "Error";
            } else if (type === "loading") {
                badge.classList.add(
                    "bg-yellow-100",
                    "text-yellow-700"
                );
                badge.innerText = "Processing";
            } else {
                badge.classList.add(
                    "bg-blue-100",
                    "text-blue-700"
                );
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
                badge.classList.add(
                    "bg-green-100",
                    "text-green-700"
                );
                badge.innerText = "Completed";
            } else if (type === "error") {
                badge.classList.add(
                    "bg-red-100",
                    "text-red-700"
                );
                badge.innerText = "Error";
            } else if (type === "loading") {
                badge.classList.add(
                    "bg-yellow-100",
                    "text-yellow-700"
                );
                badge.innerText = "Processing";
            } else {
                badge.classList.add(
                    "bg-blue-100",
                    "text-blue-700"
                );
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

        const preview = getElement("licensePreview");
        const container = getElement(
            "licensePreviewContainer"
        );

        if (preview && container) {
            if (
                preview.src &&
                preview.src.startsWith("blob:")
            ) {
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

        const formData = new FormData();
        formData.append("driver_license", file);

        try {
            const response = await fetch(
                "/enforcer/ocr/driver-license",
                {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN":
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                ?.getAttribute("content") || "",
                        Accept: "application/json",
                    },
                    credentials: "same-origin",
                }
            );

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message ||
                        "Driver's license OCR failed."
                );
            }

            const result = data.data || {};

            setFieldValue(
                "first_name",
                result.first_name
            );

            setFieldValue(
                "middle_name",
                result.middle_name
            );

            setFieldValue(
                "last_name",
                result.last_name
            );

            setFieldValue(
                "license_number",
                result.license_number
            );

            setFieldValue(
                "address",
                result.address
            );

            setFieldValue(
                "birth_date",
                result.birth_date
            );

            setDriverOcrStatus(
                "success",
                "Driver's license scanned",
                data.message ||
                    "Driver information was extracted successfully."
            );

            console.log(
                "Driver License OCR:",
                result
            );
        } catch (error) {
            console.error(
                "Driver License OCR Error:",
                error
            );

            setDriverOcrStatus(
                "error",
                "OCR failed",
                error.message ||
                    "Unable to read the driver's license."
            );
        }
    };

    // ===================================================
    // CITATION TICKET CAMERA
    // ===================================================

    window.openTicketCamera = function () {
        const input = getElement("ticket_image");

        if (!input) {
            console.error(
                "Ticket image input not found."
            );
            return;
        }

        input.setAttribute(
            "capture",
            "environment"
        );

        input.click();
    };

    // ===================================================
    // CITATION TICKET FILE
    // ===================================================

    window.openTicketFile = function () {
        const input = getElement("ticket_image");

        if (!input) {
            console.error(
                "Ticket image input not found."
            );
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

        const preview = getElement(
            "ticketPreview"
        );

        const container = getElement(
            "ticketPreviewContainer"
        );

        if (preview && container) {
            if (
                preview.src &&
                preview.src.startsWith("blob:")
            ) {
                URL.revokeObjectURL(
                    preview.src
                );
            }

            preview.src =
                URL.createObjectURL(file);

            container.classList.remove(
                "hidden"
            );
        }

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
            "Please wait while the system extracts the ticket information."
        );

        const formData = new FormData();

        formData.append(
            "ticket_image",
            file
        );

        try {
            const response = await fetch(
                "/enforcer/ocr/citation-ticket",
                {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN":
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                ?.getAttribute(
                                    "content"
                                ) || "",
                        Accept: "application/json",
                    },
                    credentials: "same-origin",
                }
            );

            const data =
                await response.json();

            if (
                !response.ok ||
                !data.success
            ) {
                throw new Error(
                    data.message ||
                        "Citation ticket OCR failed."
                );
            }

            const result =
                data.data || {};

            setFieldValue(
                "ticket_number",
                result.ticket_number
            );

            setFieldValue(
                "first_name",
                result.first_name
            );

            setFieldValue(
                "middle_name",
                result.middle_name
            );

            setFieldValue(
                "last_name",
                result.last_name
            );

            setFieldValue(
                "license_number",
                result.license_number
            );

            setFieldValue(
                "address",
                result.address
            );

            setFieldValue(
                "birth_date",
                result.birth_date
            );

            setFieldValue(
                "plate_number",
                result.plate_number
            );

            setFieldValue(
                "vehicle_type",
                result.vehicle_type
            );

            setFieldValue(
                "region_number",
                result.region_number
            );

            setFieldValue(
                "owner_name",
                result.owner_name
            );

            // Location is intentionally NOT populated
            // by OCR. Location is controlled by GPS.

            handleDetectedViolations(
                result.violations || []
            );

            setTicketOcrStatus(
                "success",
                "Citation ticket scanned",
                data.message ||
                    "Ticket information was extracted successfully."
            );

            console.log(
                "Citation Ticket OCR:",
                result
            );

            if (data.raw_text) {
                console.log(
                    "Citation Ticket OCR Raw Text:",
                    data.raw_text
                );
            }
        } catch (error) {
            console.error(
                "Citation Ticket OCR Error:",
                error
            );

            setTicketOcrStatus(
                "error",
                "OCR failed",
                error.message ||
                    "Unable to read the citation ticket."
            );
        }
    }

    // ===================================================
    // HANDLE OCR DETECTED VIOLATIONS
    // ===================================================

    function handleDetectedViolations(
        violations
    ) {
        if (
            !Array.isArray(violations) ||
            violations.length === 0
        ) {
            return;
        }

        const select =
            getElement(
                "violation_type_id"
            );

        const otherContainer =
            getElement(
                "otherViolationContainer"
            );

        const otherInput =
            getElement(
                "other_violation"
            );

        const remarks =
            getElement("remarks");

        if (!select) {
            return;
        }

        const detected =
            violations
                .map(function (violation) {
                    return String(violation)
                        .replace(
                            /\s+/g,
                            " "
                        )
                        .trim();
                })
                .filter(Boolean);

        if (
            detected.length === 0
        ) {
            return;
        }

        const options =
            Array.from(
                select.options
            );

        const matchedOptions =
            [];

        detected.forEach(
            function (
                detectedViolation
            ) {
                const cleanDetected =
                    detectedViolation
                        .toLowerCase()
                        .replace(
                            /\s+/g,
                            " "
                        )
                        .trim();

                const match =
                    options.find(
                        function (
                            option
                        ) {
                            const optionText =
                                option.textContent
                                    .toLowerCase()
                                    .replace(
                                        /\s+/g,
                                        " "
                                    )
                                    .trim();

                            return (
                                optionText.includes(
                                    cleanDetected
                                ) ||
                                cleanDetected.includes(
                                    optionText
                                )
                            );
                        }
                    );

                if (
                    match &&
                    match.value !==
                        "other"
                ) {
                    matchedOptions.push(
                        match
                    );
                }
            }
        );

        if (
            matchedOptions.length >
            0
        ) {
            select.value =
                matchedOptions[0]
                    .value;

            if (
                otherContainer
            ) {
                otherContainer.classList.add(
                    "hidden"
                );
            }

            if (
                otherInput
            ) {
                otherInput.required =
                    false;
            }
        } else {
            const otherOption =
                options.find(
                    function (
                        option
                    ) {
                        return (
                            option.value ===
                            "other"
                        );
                    }
                );

            if (otherOption) {
                select.value =
                    "other";

                if (
                    otherContainer
                ) {
                    otherContainer.classList.remove(
                        "hidden"
                    );
                }

                if (
                    otherInput
                ) {
                    otherInput.value =
                        detected.join(
                            "; "
                        );

                    otherInput.required =
                        true;
                }
            }
        }

        if (remarks) {
            const detectedText =
                detected.join("; ");

            const existingRemarks =
                remarks.value.trim();

            const label =
                "OCR Detected Violations: ";

            if (
                !existingRemarks.includes(
                    label
                )
            ) {
                remarks.value =
                    existingRemarks
                        ? `${existingRemarks}\n\n${label}${detectedText}`
                        : `${label}${detectedText}`;
            }
        }

        console.log(
            "Detected violations:",
            detected
        );
    }

    // ===================================================
    // VIOLATION TYPE - OTHER
    // ===================================================

    const violationSelect =
        getElement(
            "violation_type_id"
        );

    const otherViolationContainer =
        getElement(
            "otherViolationContainer"
        );

    const otherViolationInput =
        getElement(
            "other_violation"
        );

    function updatePrimaryOtherViolation() {
        if (
            !violationSelect ||
            !otherViolationContainer
        ) {
            return;
        }

        if (
            violationSelect.value ===
            "other"
        ) {
            otherViolationContainer.classList.remove(
                "hidden"
            );

            if (
                otherViolationInput
            ) {
                otherViolationInput.required =
                    true;
            }
        } else {
            otherViolationContainer.classList.add(
                "hidden"
            );

            if (
                otherViolationInput
            ) {
                otherViolationInput.required =
                    false;

                otherViolationInput.value =
                    "";
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
    // OFFLINE SYNC UI
    // ===================================================

    const offlinePendingBox =
        getElement(
            "offlinePendingBox"
        );

    const offlinePendingTitle =
        getElement(
            "offlinePendingTitle"
        );

    const offlinePendingMessage =
        getElement(
            "offlinePendingMessage"
        );

    const syncNowButton =
        getElement(
            "syncNowButton"
        );

    const offlineSyncingBox =
        getElement(
            "offlineSyncingBox"
        );

    const offlineSyncingMessage =
        getElement(
            "offlineSyncingMessage"
        );

    const offlineSuccessBox =
        getElement(
            "offlineSuccessBox"
        );

    const offlineSuccessMessage =
        getElement(
            "offlineSuccessMessage"
        );

    const offlineErrorBox =
        getElement(
            "offlineErrorBox"
        );

    const offlineErrorMessage =
        getElement(
            "offlineErrorMessage"
        );

    // ===================================================
    // INDEXEDDB SETTINGS
    // ===================================================

    const OFFLINE_DB_NAME =
        "TrafficEnforceNetDB";

    const OFFLINE_DB_VERSION =
        1;

    const OFFLINE_STORE_NAME =
        "pendingTickets";

    let syncInProgress =
        false;

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
            "license_number",
            "plate_number",
            "violation_type_id",
        ];

        requiredFieldIds.forEach(
            function (id) {
                const field =
                    getElement(id);

                if (field) {
                    field.required =
                        true;
                }
            }
        );

        // Primary "Other" violation.
        updatePrimaryOtherViolation();

        // Every dynamically added additional
        // violation select is required.
        const additionalViolationSelects =
            form.querySelectorAll(
                'select[name="additional_violation_type_ids[]"]'
            );

        additionalViolationSelects.forEach(
            function (select) {
                select.required =
                    true;

                const row =
                    select.closest(
                        ".additional-violation-row"
                    ) ||
                    select.parentElement;

                const additionalOtherInput =
                    row?.querySelector(
                        'input[name="additional_other_violation_names[]"]'
                    );

                if (
                    additionalOtherInput
                ) {
                    additionalOtherInput.required =
                        select.value ===
                        "other";
                }
            }
        );
    }

    setupRequiredFields();

    // ===================================================
    // HANDLE DYNAMIC ADDITIONAL VIOLATIONS
    // ===================================================

    if (form) {
        form.addEventListener(
            "change",
            function (event) {
                const target =
                    event.target;

                if (
                    target.matches(
                        'select[name="additional_violation_type_ids[]"]'
                    )
                ) {
                    target.required =
                        true;

                    const row =
                        target.closest(
                            ".additional-violation-row"
                        ) ||
                        target.parentElement;

                    const additionalOtherInput =
                        row?.querySelector(
                            'input[name="additional_other_violation_names[]"]'
                        );

                    if (
                        additionalOtherInput
                    ) {
                        additionalOtherInput.required =
                            target.value ===
                            "other";
                    }
                }
            }
        );

        // Ensure newly-created dynamic rows
        // receive the required attribute.
        if (
            window.MutationObserver
        ) {
            const observer =
                new MutationObserver(
                    function () {
                        setupRequiredFields();
                    }
                );

            observer.observe(
                form,
                {
                    childList: true,
                    subtree: true,
                }
            );
        }
    }

    // ===================================================
    // OPEN INDEXEDDB
    // ===================================================

    function openOfflineDatabase() {
        return new Promise(
            function (
                resolve,
                reject
            ) {
                if (
                    !window.indexedDB
                ) {
                    reject(
                        new Error(
                            "IndexedDB is not supported on this device."
                        )
                    );

                    return;
                }

                const request =
                    indexedDB.open(
                        OFFLINE_DB_NAME,
                        OFFLINE_DB_VERSION
                    );

                request.onupgradeneeded =
                    function (
                        event
                    ) {
                        const db =
                            event.target
                                .result;

                        if (
                            !db.objectStoreNames.contains(
                                OFFLINE_STORE_NAME
                            )
                        ) {
                            db.createObjectStore(
                                OFFLINE_STORE_NAME,
                                {
                                    keyPath:
                                        "id",
                                    autoIncrement:
                                        true,
                                }
                            );
                        }
                    };

                request.onsuccess =
                    function () {
                        resolve(
                            request.result
                        );
                    };

                request.onerror =
                    function () {
                        reject(
                            request.error ||
                                new Error(
                                    "Unable to open offline storage."
                                )
                        );
                    };
            }
        );
    }

    // ===================================================
    // SAVE TICKET OFFLINE
    // ===================================================

    async function saveTicketOffline() {
        if (!form) {
            throw new Error(
                "Issue ticket form was not found."
            );
        }

        const formData =
            new FormData(form);

        const fields = {};
        const files = {};

        for (
            const [
                name,
                value,
            ] of formData.entries()
        ) {
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

                if (
                    name.endsWith(
                        "[]"
                    )
                ) {
                    if (!files[name]) {
                        files[name] = [];
                    }

                    files[name].push(
                        fileData
                    );
                } else {
                    files[name] =
                        fileData;
                }
            } else {
                if (
                    fields[name] ===
                    undefined
                ) {
                    fields[name] =
                        String(value);
                } else {
                    if (
                        !Array.isArray(
                            fields[name]
                        )
                    ) {
                        fields[name] = [
                            fields[name],
                        ];
                    }

                    fields[name].push(
                        String(value)
                    );
                }
            }
        }

        const db =
            await openOfflineDatabase();

        return new Promise(
            function (
                resolve,
                reject
            ) {
                const transaction =
                    db.transaction(
                        OFFLINE_STORE_NAME,
                        "readwrite"
                    );

                const store =
                    transaction.objectStore(
                        OFFLINE_STORE_NAME
                    );

                const request =
                    store.add({
                        fields:
                            fields,

                        files:
                            files,

                        action:
                            form.action,

                        createdAt:
                            new Date().toISOString(),
                    });

                request.onsuccess =
                    function () {
                        resolve(
                            request.result
                        );
                    };

                request.onerror =
                    function () {
                        reject(
                            request.error ||
                                new Error(
                                    "Unable to save ticket offline."
                                )
                        );
                    };
            }
        );
    }

    // ===================================================
    // GET PENDING TICKETS
    // ===================================================

    async function getPendingTickets() {
        const db =
            await openOfflineDatabase();

        return new Promise(
            function (
                resolve,
                reject
            ) {
                const transaction =
                    db.transaction(
                        OFFLINE_STORE_NAME,
                        "readonly"
                    );

                const store =
                    transaction.objectStore(
                        OFFLINE_STORE_NAME
                    );

                const request =
                    store.getAll();

                request.onsuccess =
                    function () {
                        resolve(
                            request.result ||
                                []
                        );
                    };

                request.onerror =
                    function () {
                        reject(
                            request.error ||
                                new Error(
                                    "Unable to read pending tickets."
                                )
                        );
                    };
            }
        );
    }

    // ===================================================
    // DELETE SYNCED TICKET
    // ===================================================

    async function deletePendingTicket(
        id
    ) {
        const db =
            await openOfflineDatabase();

        return new Promise(
            function (
                resolve,
                reject
            ) {
                const transaction =
                    db.transaction(
                        OFFLINE_STORE_NAME,
                        "readwrite"
                    );

                const store =
                    transaction.objectStore(
                        OFFLINE_STORE_NAME
                    );

                const request =
                    store.delete(id);

                request.onsuccess =
                    function () {
                        resolve();
                    };

                request.onerror =
                    function () {
                        reject(
                            request.error ||
                                new Error(
                                    "Unable to remove synced ticket."
                                )
                        );
                    };
            }
        );
    }

    // ===================================================
    // GET CSRF TOKEN
    // ===================================================

    function getCsrfToken() {
        const meta =
            document.querySelector(
                'meta[name="csrf-token"]'
            );

        if (meta) {
            const token =
                meta.getAttribute(
                    "content"
                );

            if (token) {
                return token;
            }
        }

        const tokenInput =
            form?.querySelector(
                'input[name="_token"]'
            );

        return tokenInput?.value ||
            "";
    }

    // ===================================================
    // BUILD FORMDATA FOR SYNC
    // ===================================================

    function buildSyncFormData(
        ticket
    ) {
        const syncFormData =
            new FormData();

        Object.entries(
            ticket.fields || {}
        ).forEach(
            function (
                [
                    name,
                    value,
                ]
            ) {
                if (
                    name === "_token"
                ) {
                    return;
                }

                if (
                    Array.isArray(value)
                ) {
                    value.forEach(
                        function (
                            item
                        ) {
                            syncFormData.append(
                                name,
                                item
                            );
                        }
                    );
                } else {
                    syncFormData.append(
                        name,
                        value
                    );
                }
            }
        );

        const csrfToken =
            getCsrfToken();

        if (csrfToken) {
            syncFormData.append(
                "_token",
                csrfToken
            );
        }

        Object.entries(
            ticket.files || {}
        ).forEach(
            function (
                [
                    name,
                    fileData,
                ]
            ) {
                if (
                    Array.isArray(
                        fileData
                    )
                ) {
                    fileData.forEach(
                        function (
                            item
                        ) {
                            const file =
                                new File(
                                    [item.blob],
                                    item.name,
                                    {
                                        type:
                                            item.type,
                                        lastModified:
                                            item.lastModified,
                                    }
                                );

                            syncFormData.append(
                                name,
                                file
                            );
                        }
                    );
                } else {
                    const file =
                        new File(
                            [fileData.blob],
                            fileData.name,
                            {
                                type:
                                    fileData.type,
                                lastModified:
                                    fileData.lastModified,
                            }
                        );

                    syncFormData.append(
                        name,
                        file
                    );
                }
            }
        );

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

    function showPendingTickets(
        count
    ) {
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
                        count === 1
                            ? ""
                            : "s"
                    } waiting to sync.`;
            } else {
                offlinePendingMessage.innerText =
                    `You are offline. ${count} ticket${
                        count === 1
                            ? ""
                            : "s"
                    } saved on this device and waiting to sync.`;
            }
        }

        if (syncNowButton) {
            if (navigator.onLine) {
                syncNowButton.classList.remove(
                    "hidden"
                );

                syncNowButton.disabled =
                    false;

                syncNowButton.innerText =
                    "Sync Now";
            } else {
                syncNowButton.classList.add(
                    "hidden"
                );

                syncNowButton.disabled =
                    true;
            }
        }
    }

    // ===================================================
    // SHOW SYNCING
    // ===================================================

    function showSyncing(
        count
    ) {
        hideOfflineStatus();

        if (offlineSyncingBox) {
            offlineSyncingBox.classList.remove(
                "hidden"
            );
        }

        if (offlineSyncingMessage) {
            offlineSyncingMessage.innerText =
                `Uploading ${count} pending ticket${
                    count === 1
                        ? ""
                        : "s"
                }...`;
        }
    }

    // ===================================================
    // SHOW SUCCESS
    // ===================================================

    function showSyncSuccess(
        count
    ) {
        hideOfflineStatus();

        if (offlineSuccessBox) {
            offlineSuccessBox.classList.remove(
                "hidden"
            );
        }

        if (offlineSuccessMessage) {
            offlineSuccessMessage.innerText =
                `${count} ticket${
                    count === 1
                        ? ""
                        : "s"
                } synchronized successfully.`;
        }

        setTimeout(
            function () {
                updatePendingSyncCount();
            },
            3000
        );
    }

    // ===================================================
    // SHOW ERROR
    // ===================================================

    function showSyncError(
        message
    ) {
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

            const count =
                tickets.length;

            if (count === 0) {
                hideOfflineStatus();
                return;
            }

            showPendingTickets(
                count
            );
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

    async function syncOneTicket(
        ticket
    ) {
        if (!navigator.onLine) {
            throw new Error(
                "Internet connection is unavailable."
            );
        }

        const syncFormData =
            buildSyncFormData(
                ticket
            );

        const action =
            ticket.action ||
            form?.action ||
            "/enforcer/issue-ticket";

        const response =
            await fetch(
                action,
                {
                    method: "POST",

                    body:
                        syncFormData,

                    credentials:
                        "same-origin",

                    headers: {
                        Accept:
                            "application/json",

                        "X-Requested-With":
                            "XMLHttpRequest",
                    },
                }
            );

        if (!response.ok) {
            let message =
                `Server returned HTTP ${response.status}.`;

            try {
                const data =
                    await response.json();

                if (
                    data.message
                ) {
                    message =
                        data.message;
                }

                if (
                    data.errors
                ) {
                    const firstError =
                        Object.values(
                            data.errors
                        )
                            .flat()
                            .find(
                                Boolean
                            );

                    if (
                        firstError
                    ) {
                        message =
                            firstError;
                    }
                }
            } catch (
                error
            ) {
                // Response was not JSON.
            }

            throw new Error(
                message
            );
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

        syncInProgress =
            true;

        try {
            const tickets =
                await getPendingTickets();

            if (
                tickets.length === 0
            ) {
                hideOfflineStatus();
                return;
            }

            showSyncing(
                tickets.length
            );

            let syncedCount =
                0;

            let lastError =
                "";

            for (
                const ticket
                of tickets
            ) {
                if (
                    !navigator.onLine
                ) {
                    lastError =
                        "Internet connection was lost during synchronization.";

                    break;
                }

                try {
                    await syncOneTicket(
                        ticket
                    );

                    // IMPORTANT:
                    // Delete only after Laravel
                    // successfully accepts the ticket.
                    await deletePendingTicket(
                        ticket.id
                    );

                    syncedCount++;
                } catch (
                    error
                ) {
                    console.error(
                        `Failed to sync ticket ${ticket.id}:`,
                        error
                    );

                    lastError =
                        error.message ||
                        "Unable to synchronize ticket.";

                    // Keep failed ticket in IndexedDB.
                    // Stop here and retry later.
                    break;
                }
            }

            const remainingTickets =
                await getPendingTickets();

            const remainingCount =
                remainingTickets.length;

            if (
                remainingCount === 0
            ) {
                if (
                    syncedCount > 0
                ) {
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

            setTimeout(
                function () {
                    updatePendingSyncCount();
                },
                2500
            );
        } catch (
            error
        ) {
            console.error(
                "Offline synchronization error:",
                error
            );

            showSyncError(
                error.message ||
                    "Unable to synchronize offline tickets."
            );
        } finally {
            syncInProgress =
                false;
        }
    }

    // ===================================================
    // MANUAL SYNC BUTTON
    // ===================================================

    if (syncNowButton) {
        syncNowButton.addEventListener(
            "click",
            async function (
                event
            ) {
                event.preventDefault();

                if (
                    syncInProgress
                ) {
                    return;
                }

                if (
                    !navigator.onLine
                ) {
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

        // Reapply all required rules.
        setupRequiredFields();

        // -------------------------------------------------
        // Validate required text fields.
        // This also catches whitespace-only values.
        // -------------------------------------------------

        const requiredTextFieldIds = [
            "first_name",
            "last_name",
            "license_number",
            "plate_number",
        ];

        for (
            const id
            of requiredTextFieldIds
        ) {
            const field =
                getElement(id);

            if (
                field &&
                field.value.trim() === ""
            ) {
                field.setCustomValidity(
                    "Please fill out this field."
                );

                field.reportValidity();

                field.setCustomValidity(
                    ""
                );

                return false;
            }

            if (field) {
                field.setCustomValidity(
                    ""
                );
            }
        }

        // -------------------------------------------------
        // Browser native validation.
        // -------------------------------------------------

        if (
            !form.checkValidity()
        ) {
            form.reportValidity();
            return false;
        }

        // -------------------------------------------------
        // Primary "Other" violation.
        // -------------------------------------------------

        const primaryViolation =
            getElement(
                "violation_type_id"
            );

        const primaryOther =
            getElement(
                "other_violation"
            );

        if (
            primaryViolation &&
            primaryViolation.value ===
                "other"
        ) {
            if (
                !primaryOther ||
                primaryOther.value.trim() ===
                    ""
            ) {
                if (
                    primaryOther
                ) {
                    primaryOther.required =
                        true;

                    primaryOther.setCustomValidity(
                        "Please specify the other violation."
                    );

                    primaryOther.reportValidity();

                    primaryOther.setCustomValidity(
                        ""
                    );
                }

                return false;
            }
        }

        // -------------------------------------------------
        // Additional violations.
        // -------------------------------------------------

        const additionalViolationSelects =
            form.querySelectorAll(
                'select[name="additional_violation_type_ids[]"]'
            );

        for (
            const select
            of additionalViolationSelects
        ) {
            select.required =
                true;

            if (
                !select.value
            ) {
                select.reportValidity();
                return false;
            }

            if (
                select.value ===
                "other"
            ) {
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
                    otherInput.value.trim() ===
                        ""
                ) {
                    if (
                        otherInput
                    ) {
                        otherInput.required =
                            true;

                        otherInput.setCustomValidity(
                            "Please specify the other violation."
                        );

                        otherInput.reportValidity();

                        otherInput.setCustomValidity(
                            ""
                        );
                    }

                    return false;
                }
            }
        }

        return true;
    }

    // ===================================================
    // FORM SUBMISSION
    // ===================================================

    if (form) {
        form.addEventListener(
            "submit",
            async function (
                event
            ) {
                // -------------------------------------------------
                // ALWAYS VALIDATE FIRST
                // -------------------------------------------------

                if (
                    !validateTicketForm()
                ) {
                    event.preventDefault();
                    return;
                }

                // -------------------------------------------------
                // ONLINE
                // -------------------------------------------------
                //
                // Let Laravel handle normal online submission.
                // -------------------------------------------------

                if (
                    navigator.onLine
                ) {
                    return;
                }

                // -------------------------------------------------
                // OFFLINE
                // -------------------------------------------------

                event.preventDefault();

                if (
                    syncInProgress
                ) {
                    return;
                }

                try {
                    await saveTicketOffline();

                    await updatePendingSyncCount();

                    const tickets =
                        await getPendingTickets();

                    const count =
                        tickets.length;

                    if (
                        offlinePendingMessage
                    ) {
                        offlinePendingMessage.innerText =
                            `You are offline. Your ticket has been saved on this device. ${count} ticket${
                                count === 1
                                    ? ""
                                    : "s"
                            } waiting to sync.`;
                    }

                    console.log(
                        "Ticket saved successfully for offline synchronization."
                    );

                    // -------------------------------------------------
                    // Clear only AFTER successful IndexedDB save.
                    // -------------------------------------------------

                    form.reset();

                    setupRequiredFields();

                    if (
                        otherViolationContainer
                    ) {
                        otherViolationContainer.classList.add(
                            "hidden"
                        );
                    }

                    if (
                        otherViolationInput
                    ) {
                        otherViolationInput.required =
                            false;

                        otherViolationInput.value =
                            "";
                    }
                } catch (
                    error
                ) {
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
    // INTERNET CONNECTION RESTORED
    // ===================================================

    window.addEventListener(
        "online",
        function () {
            console.log(
                "Internet connection restored."
            );

            updateConnectionStatus();

            setTimeout(
                function () {
                    syncPendingTickets();
                },
                1500
            );
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

        setTimeout(
            function () {
                syncPendingTickets();
            },
            1000
        );
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
        getElement(
            "gpsStatusBadge"
        );

    const gpsCoordinates =
        getElement(
            "gpsCoordinates"
        );

    const locationIcon =
        getElement(
            "locationIcon"
        );

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
            locationIcon.innerText =
                "⚠️";
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
        locationIcon.innerText =
            "⏳";
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

    const TARGET_ACCURACY =
        10;

    const MAX_GPS_TIME =
        20000;

    let bestPosition =
        null;

    let bestAccuracy =
        Infinity;

    let gpsWatchId =
        null;

    let gpsFinished =
        false;

    // ===================================================
    // FINISH GPS CAPTURE
    // ===================================================

    function finishGPS(
        position
    ) {
        if (gpsFinished) {
            return;
        }

        gpsFinished =
            true;

        if (
            gpsWatchId !==
            null
        ) {
            navigator.geolocation.clearWatch(
                gpsWatchId
            );

            gpsWatchId =
                null;
        }

        // -------------------------------------------------
        // NO GPS POSITION
        // -------------------------------------------------

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
                locationIcon.innerText =
                    "⚠️";
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

        // -------------------------------------------------
        // FINAL GPS VALUES
        // -------------------------------------------------

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

        // -------------------------------------------------
        // SAVE COORDINATES TO FORM
        // -------------------------------------------------

        latitudeInput.value =
            latitude;

        longitudeInput.value =
            longitude;

        // -------------------------------------------------
        // GPS SUCCESS STATUS
        // -------------------------------------------------

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

        // -------------------------------------------------
        // START REVERSE GEOCODING
        // -------------------------------------------------

        if (locationInput) {
            locationInput.value =
                "Detecting address...";
        }

        if (locationIcon) {
            locationIcon.innerText =
                "⏳";
        }

        const reverseGeocodeUrl =
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${encodeURIComponent(
                latitude
            )}&lon=${encodeURIComponent(
                longitude
            )}&zoom=18&addressdetails=1`;

        fetch(
            reverseGeocodeUrl,
            {
                headers: {
                    "Accept-Language":
                        "en",
                },
            }
        )
            .then(
                function (
                    response
                ) {
                    if (
                        !response.ok
                    ) {
                        throw new Error(
                            `HTTP ${response.status}`
                        );
                    }

                    return response.json();
                }
            )
            .then(
                function (data) {
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
                            address.state ||
                            "";

                        const formattedAddress =
                            [
                                road,
                                barangay,
                                city,
                                province,
                            ]
                                .filter(
                                    Boolean
                                )
                                .join(
                                    ", "
                                );

                        if (
                            locationInput
                        ) {
                            locationInput.value =
                                formattedAddress ||
                                "Address unavailable";
                        }

                        if (
                            locationIcon
                        ) {
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
                        if (
                            locationInput
                        ) {
                            locationInput.value =
                                "Address unavailable";
                        }

                        if (
                            locationIcon
                        ) {
                            locationIcon.innerText =
                                "⚠️";
                        }
                    }
                }
            )
            .catch(
                function (
                    error
                ) {
                    console.error(
                        "Reverse geocoding error:",
                        error
                    );

                    if (
                        locationInput
                    ) {
                        locationInput.value =
                            "Unable to get address";
                    }

                    if (
                        locationIcon
                    ) {
                        locationIcon.innerText =
                            "⚠️";
                    }
                }
            );
    }

    // ===================================================
    // GPS ERROR HANDLER
    // ===================================================

    function handleGPSError(
        error
    ) {
        console.error(
            "GPS Error:",
            error.code,
            error.message
        );

        if (gpsFinished) {
            return;
        }

        // -------------------------------------------------
        // IF WE ALREADY HAVE A READING, KEEP IT
        // -------------------------------------------------

        if (bestPosition) {
            finishGPS(
                bestPosition
            );

            return;
        }

        // -------------------------------------------------
        // PERMISSION DENIED
        // -------------------------------------------------

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

        // -------------------------------------------------
        // POSITION UNAVAILABLE
        // -------------------------------------------------

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

        // -------------------------------------------------
        // TIMEOUT
        // -------------------------------------------------

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

        // -------------------------------------------------
        // UNKNOWN ERROR
        // -------------------------------------------------

        finishGPS(null);
    }

    // ===================================================
    // START HIGH-ACCURACY GPS WATCH
    // ===================================================

    gpsWatchId =
        navigator.geolocation.watchPosition(
            function (
                position
            ) {
                if (gpsFinished) {
                    return;
                }

                const accuracy =
                    position.coords
                        .accuracy;

                console.log(
                    "GPS Reading:",
                    position.coords
                        .latitude,
                    position.coords
                        .longitude,
                    "Accuracy:",
                    accuracy,
                    "meters"
                );

                // -------------------------------------------------
                // KEEP THE MOST ACCURATE READING
                // -------------------------------------------------

                if (
                    !bestPosition ||
                    accuracy <
                        bestAccuracy
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

                    if (
                        gpsCoordinates
                    ) {
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

                // -------------------------------------------------
                // STOP ONCE TARGET ACCURACY IS REACHED
                // -------------------------------------------------

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

            function (
                error
            ) {
                handleGPSError(
                    error
                );
            },

            {
                enableHighAccuracy:
                    true,

                timeout:
                    MAX_GPS_TIME,

                maximumAge:
                    0,
            }
        );

    // ===================================================
    // GPS MAXIMUM WAIT TIMER
    // ===================================================

    setTimeout(
        function () {
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
        },
        MAX_GPS_TIME
    );
});