// =======================================================
// TRAFFICENFORCENET
// ENFORCER - ISSUE TICKET JAVASCRIPT
// =======================================================

document.addEventListener('DOMContentLoaded', function () {

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
            String(value).trim() !== ''
        ) {
            field.value = String(value).trim();
        }
    }


    // ===================================================
    // DRIVER'S LICENSE OCR STATUS
    // ===================================================

    function setDriverOcrStatus(
        type,
        title,
        message
    ) {

        const badge = getElement('ocrBadge');
        const status = getElement('ocrStatus');
        const icon = getElement('ocrIcon');
        const ocrTitle = getElement('ocrTitle');
        const ocrMessage = getElement('ocrMessage');

        if (ocrTitle) {
            ocrTitle.innerText = title;
        }

        if (ocrMessage) {
            ocrMessage.innerText = message;
        }

        if (status) {

            status.classList.remove(
                'bg-blue-50',
                'bg-green-50',
                'bg-red-50',
                'bg-yellow-50'
            );

            if (type === 'success') {
                status.classList.add('bg-green-50');

            } else if (type === 'error') {
                status.classList.add('bg-red-50');

            } else if (type === 'loading') {
                status.classList.add('bg-yellow-50');

            } else {
                status.classList.add('bg-blue-50');
            }
        }

        if (badge) {

            badge.classList.remove(
                'bg-blue-100',
                'bg-green-100',
                'bg-red-100',
                'bg-yellow-100',
                'text-blue-700',
                'text-green-700',
                'text-red-700',
                'text-yellow-700'
            );

            if (type === 'success') {

                badge.classList.add(
                    'bg-green-100',
                    'text-green-700'
                );

                badge.innerText = 'Completed';

            } else if (type === 'error') {

                badge.classList.add(
                    'bg-red-100',
                    'text-red-700'
                );

                badge.innerText = 'Error';

            } else if (type === 'loading') {

                badge.classList.add(
                    'bg-yellow-100',
                    'text-yellow-700'
                );

                badge.innerText = 'Processing';

            } else {

                badge.classList.add(
                    'bg-blue-100',
                    'text-blue-700'
                );

                badge.innerText = 'Ready';
            }
        }

        if (icon) {

            if (type === 'success') {
                icon.innerText = '✓';

            } else if (type === 'error') {
                icon.innerText = '✕';

            } else if (type === 'loading') {
                icon.innerText = '⏳';

            } else {
                icon.innerText = 'ℹ';
            }
        }
    }


    // ===================================================
    // CITATION TICKET OCR STATUS
    // ===================================================

    function setTicketOcrStatus(
        type,
        title,
        message
    ) {

        const badge = getElement('ticketOcrBadge');
        const status = getElement('ticketOcrStatus');
        const icon = getElement('ticketOcrIcon');
        const ocrTitle = getElement('ticketOcrTitle');
        const ocrMessage = getElement('ticketOcrMessage');

        if (ocrTitle) {
            ocrTitle.innerText = title;
        }

        if (ocrMessage) {
            ocrMessage.innerText = message;
        }

        if (status) {

            status.classList.remove(
                'bg-blue-50',
                'bg-green-50',
                'bg-red-50',
                'bg-yellow-50'
            );

            if (type === 'success') {
                status.classList.add('bg-green-50');

            } else if (type === 'error') {
                status.classList.add('bg-red-50');

            } else if (type === 'loading') {
                status.classList.add('bg-yellow-50');

            } else {
                status.classList.add('bg-blue-50');
            }
        }

        if (badge) {

            badge.classList.remove(
                'bg-blue-100',
                'bg-green-100',
                'bg-red-100',
                'bg-yellow-100',
                'text-blue-700',
                'text-green-700',
                'text-red-700',
                'text-yellow-700'
            );

            if (type === 'success') {

                badge.classList.add(
                    'bg-green-100',
                    'text-green-700'
                );

                badge.innerText = 'Completed';

            } else if (type === 'error') {

                badge.classList.add(
                    'bg-red-100',
                    'text-red-700'
                );

                badge.innerText = 'Error';

            } else if (type === 'loading') {

                badge.classList.add(
                    'bg-yellow-100',
                    'text-yellow-700'
                );

                badge.innerText = 'Processing';

            } else {

                badge.classList.add(
                    'bg-blue-100',
                    'text-blue-700'
                );

                badge.innerText = 'Ready';
            }
        }

        if (icon) {

            if (type === 'success') {
                icon.innerText = '✓';

            } else if (type === 'error') {
                icon.innerText = '✕';

            } else if (type === 'loading') {
                icon.innerText = '⏳';

            } else {
                icon.innerText = 'ℹ';
            }
        }
    }


    // ===================================================
    // DRIVER'S LICENSE CAMERA
    // ===================================================

    window.openLicenseCamera = function () {

        const input =
            getElement('driver_license');

        if (!input) {
            console.error(
                'Driver license input not found.'
            );
            return;
        }

        input.setAttribute(
            'capture',
            'environment'
        );

        input.click();
    };


    // ===================================================
    // DRIVER'S LICENSE FILE
    // ===================================================

    window.openLicenseFile = function () {

        const input =
            getElement('driver_license');

        if (!input) {
            console.error(
                'Driver license input not found.'
            );
            return;
        }

        input.removeAttribute(
            'capture'
        );

        input.click();
    };


    // ===================================================
    // DRIVER'S LICENSE OCR
    // ===================================================

    window.processDriverLicense = async function (event) {

        const input = event.target;

        const file =
            input?.files?.[0];

        if (!file) {
            return;
        }

        // -----------------------------------------------
        // PREVIEW
        // -----------------------------------------------

        const preview =
            getElement('licensePreview');

        const container =
            getElement('licensePreviewContainer');

        if (preview && container) {

            if (preview.src) {
                URL.revokeObjectURL(
                    preview.src
                );
            }

            preview.src =
                URL.createObjectURL(file);

            container.classList.remove(
                'hidden'
            );
        }


        // -----------------------------------------------
        // STATUS
        // -----------------------------------------------

        setDriverOcrStatus(
            'loading',
            'Reading driver\'s license...',
            'Please wait while the system extracts the driver information.'
        );


        // -----------------------------------------------
        // FORM DATA
        // -----------------------------------------------

        const formData =
            new FormData();

        formData.append(
            'driver_license',
            file
        );


        try {

            // -------------------------------------------
            // SEND TO LARAVEL
            // -------------------------------------------

            const response =
                await fetch(
                    '/enforcer/ocr/driver-license',
                    {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN':
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                )?.getAttribute(
                                    'content'
                                ) || '',

                            'Accept':
                                'application/json'
                        }
                    }
                );


            // -------------------------------------------
            // READ RESPONSE
            // -------------------------------------------

            const data =
                await response.json();


            // -------------------------------------------
            // ERROR RESPONSE
            // -------------------------------------------

            if (
                !response.ok ||
                !data.success
            ) {

                throw new Error(
                    data.message ||
                    'Driver\'s license OCR failed.'
                );
            }


            // -------------------------------------------
            // OCR DATA
            // -------------------------------------------

            const result =
                data.data || {};


            // -------------------------------------------
            // POPULATE DRIVER FIELDS
            // -------------------------------------------

            setFieldValue(
                'first_name',
                result.first_name
            );

            setFieldValue(
                'middle_name',
                result.middle_name
            );

            setFieldValue(
                'last_name',
                result.last_name
            );

            setFieldValue(
                'license_number',
                result.license_number
            );

            setFieldValue(
                'address',
                result.address
            );

            setFieldValue(
                'birth_date',
                result.birth_date
            );


            // -------------------------------------------
            // SUCCESS
            // -------------------------------------------

            setDriverOcrStatus(
                'success',
                'Driver\'s license scanned',
                data.message ||
                'Driver information was extracted successfully.'
            );


            console.log(
                'Driver License OCR:',
                result
            );

        } catch (error) {

            console.error(
                'Driver License OCR Error:',
                error
            );

            setDriverOcrStatus(
                'error',
                'OCR failed',
                error.message ||
                'Unable to read the driver\'s license.'
            );
        }
    };


    // ===================================================
    // CITATION TICKET CAMERA
    // ===================================================

    window.openTicketCamera = function () {

        const input =
            getElement('ticket_image');

        if (!input) {
            console.error(
                'Ticket image input not found.'
            );
            return;
        }

        input.setAttribute(
            'capture',
            'environment'
        );

        input.click();
    };


    // ===================================================
    // CITATION TICKET FILE
    // ===================================================

    window.openTicketFile = function () {

        const input =
            getElement('ticket_image');

        if (!input) {
            console.error(
                'Ticket image input not found.'
            );
            return;
        }

        input.removeAttribute(
            'capture'
        );

        input.click();
    };


    // ===================================================
    // CITATION TICKET PREVIEW + OCR
    // ===================================================

    window.previewTicket = function (event) {

        const input = event.target;

        const file =
            input?.files?.[0];

        if (!file) {
            return;
        }


        // -----------------------------------------------
        // PREVIEW
        // -----------------------------------------------

        const preview =
            getElement('ticketPreview');

        const container =
            getElement('ticketPreviewContainer');

        if (preview && container) {

            if (preview.src) {
                URL.revokeObjectURL(
                    preview.src
                );
            }

            preview.src =
                URL.createObjectURL(file);

            container.classList.remove(
                'hidden'
            );
        }


        // -----------------------------------------------
        // START OCR
        // -----------------------------------------------

        processCitationTicket(
            file
        );
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
            'loading',
            'Reading citation ticket...',
            'Please wait while the system extracts the ticket information.'
        );


        // -----------------------------------------------
        // FORM DATA
        // -----------------------------------------------

        const formData =
            new FormData();

        formData.append(
            'ticket_image',
            file
        );


        try {

            // -------------------------------------------
            // SEND TO LARAVEL
            // -------------------------------------------

            const response =
                await fetch(
                    '/enforcer/ocr/citation-ticket',
                    {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN':
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                )?.getAttribute(
                                    'content'
                                ) || '',

                            'Accept':
                                'application/json'
                        }
                    }
                );


            // -------------------------------------------
            // READ RESPONSE
            // -------------------------------------------

            const data =
                await response.json();


            // -------------------------------------------
            // ERROR RESPONSE
            // -------------------------------------------

            if (
                !response.ok ||
                !data.success
            ) {

                throw new Error(
                    data.message ||
                    'Citation ticket OCR failed.'
                );
            }


            // -------------------------------------------
            // OCR RESULT
            // -------------------------------------------

            const result =
                data.data || {};


            // -------------------------------------------
            // POPULATE TICKET NUMBER
            // -----------------------------------------------

            setFieldValue(
                'ticket_number',
                result.ticket_number
            );


            // -------------------------------------------
            // POPULATE DRIVER INFORMATION
            // -------------------------------------------

            setFieldValue(
                'first_name',
                result.first_name
            );

            setFieldValue(
                'middle_name',
                result.middle_name
            );

            setFieldValue(
                'last_name',
                result.last_name
            );

            setFieldValue(
                'license_number',
                result.license_number
            );

            setFieldValue(
                'address',
                result.address
            );

            setFieldValue(
                'birth_date',
                result.birth_date
            );


            // -------------------------------------------
            // POPULATE VEHICLE INFORMATION
            // -------------------------------------------

            setFieldValue(
                'plate_number',
                result.plate_number
            );

            setFieldValue(
                'vehicle_type',
                result.vehicle_type
            );

            setFieldValue(
                'region_number',
                result.region_number
            );

            setFieldValue(
                'owner_name',
                result.owner_name
            );


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

            handleDetectedViolations(
                result.violations || []
            );


            // -------------------------------------------
            // SUCCESS
            // -------------------------------------------

            setTicketOcrStatus(
                'success',
                'Citation ticket scanned',
                data.message ||
                'Ticket information was extracted successfully.'
            );


            console.log(
                'Citation Ticket OCR:',
                result
            );


            // -------------------------------------------
            // RAW OCR TEXT
            // -------------------------------------------

            if (data.raw_text) {

                console.log(
                    'Citation Ticket OCR Raw Text:',
                    data.raw_text
                );
            }

        } catch (error) {

            console.error(
                'Citation Ticket OCR Error:',
                error
            );

            setTicketOcrStatus(
                'error',
                'OCR failed',
                error.message ||
                'Unable to read the citation ticket.'
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
            getElement('violation_type_id');

        const otherContainer =
            getElement('otherViolationContainer');

        const otherInput =
            getElement('other_violation');

        const remarks =
            getElement('remarks');


        if (!select) {
            return;
        }


        // -----------------------------------------------
        // NORMALIZE VIOLATIONS
        // -----------------------------------------------

        const detected =
            violations
                .map(function (violation) {

                    return String(violation)
                        .replace(/\s+/g, ' ')
                        .trim();

                })
                .filter(Boolean);


        if (detected.length === 0) {
            return;
        }


        // -----------------------------------------------
        // TRY TO MATCH EXISTING VIOLATION TYPE
        // -----------------------------------------------

        const options =
            Array.from(
                select.options
            );


        const matchedOptions = [];


        detected.forEach(
            function (detectedViolation) {

                const cleanDetected =
                    detectedViolation
                        .toLowerCase()
                        .replace(/\s+/g, ' ')
                        .trim();


                const match =
                    options.find(
                        function (option) {

                            const optionText =
                                option.textContent
                                    .toLowerCase()
                                    .replace(/\s+/g, ' ')
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
                    match.value !== 'other'
                ) {

                    matchedOptions.push(
                        match
                    );
                }
            }
        );


        // -----------------------------------------------
        // SELECT FIRST MATCH
        // -----------------------------------------------

        if (
            matchedOptions.length > 0
        ) {

            select.value =
                matchedOptions[0].value;


            if (otherContainer) {

                otherContainer.classList.add(
                    'hidden'
                );
            }
        }


        // -----------------------------------------------
        // IF NO MATCH, USE OTHER
        // -----------------------------------------------

        else {

            const otherOption =
                options.find(
                    function (option) {
                        return option.value === 'other';
                    }
                );

            if (otherOption) {

                select.value =
                    'other';

                if (otherContainer) {

                    otherContainer.classList.remove(
                        'hidden'
                    );
                }

                if (otherInput) {

                    otherInput.value =
                        detected.join('; ');
                }
            }
        }


        // -----------------------------------------------
        // PUT DETECTED VIOLATIONS IN REMARKS
        // -----------------------------------------------

        if (remarks) {

            const detectedText =
                detected.join('; ');

            const existingRemarks =
                remarks.value.trim();

            const label =
                'OCR Detected Violations: ';


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
            'Detected violations:',
            detected
        );
    }


    // ===================================================
    // VIOLATION TYPE - OTHER
    // ===================================================

    const violationSelect =
        getElement('violation_type_id');

    const otherContainer =
        getElement('otherViolationContainer');


    if (
        violationSelect &&
        otherContainer
    ) {

        violationSelect.addEventListener(
            'change',
            function () {

                if (
                    this.value === 'other'
                ) {

                    otherContainer.classList.remove(
                        'hidden'
                    );

                } else {

                    otherContainer.classList.add(
                        'hidden'
                    );
                }
            }
        );
    }


    // ===================================================
    // GPS ELEMENTS
    // ===================================================

    const locationInput =
        getElement('location');

    const latitudeInput =
        getElement('latitude');

    const longitudeInput =
        getElement('longitude');

    const gpsBadge =
        getElement('gpsStatusBadge');

    const gpsCoordinates =
        getElement('gpsCoordinates');

    const locationIcon =
        getElement('locationIcon');


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
                'Unavailable';

            gpsBadge.classList.remove(
                'bg-yellow-50',
                'text-yellow-700'
            );

            gpsBadge.classList.add(
                'bg-red-50',
                'text-red-700'
            );
        }


        if (locationInput) {

            locationInput.value =
                'GPS is not supported on this device';
        }


        if (locationIcon) {

            locationIcon.innerText =
                '⚠️';
        }

        return;
    }


    // ===================================================
    // INITIAL GPS STATUS
    // ===================================================

    if (gpsBadge) {

        gpsBadge.innerText =
            'Detecting';
    }


    if (locationInput) {

        locationInput.value =
            'Detecting current location...';
    }


    if (locationIcon) {

        locationIcon.innerText =
            '⏳';
    }


    // ===================================================
    // GET HIGH-ACCURACY GPS
    // ===================================================

    navigator.geolocation.getCurrentPosition(

        // =================================================
        // GPS SUCCESS
        // =================================================

        function (position) {

            const latitude =
                position.coords.latitude;

            const longitude =
                position.coords.longitude;

            const accuracy =
                position.coords.accuracy;


            // =================================================
            // GPS DEBUG
            // =================================================

            console.log(
                'GPS Latitude:',
                latitude
            );

            console.log(
                'GPS Longitude:',
                longitude
            );

            console.log(
                'GPS Accuracy:',
                accuracy,
                'meters'
            );


            // =================================================
            // SAVE COORDINATES
            // =================================================

            latitudeInput.value =
                latitude;

            longitudeInput.value =
                longitude;


            // =================================================
            // GPS BADGE
            // =================================================

            if (gpsBadge) {

                gpsBadge.innerText =
                    `Captured ±${Math.round(accuracy)}m`;

                gpsBadge.classList.remove(
                    'bg-yellow-50',
                    'text-yellow-700',
                    'bg-red-50',
                    'text-red-700'
                );

                gpsBadge.classList.add(
                    'bg-green-50',
                    'text-green-700'
                );
            }


            // =================================================
            // GPS COORDINATE INFORMATION
            // =================================================

            if (gpsCoordinates) {

                gpsCoordinates.classList.remove(
                    'hidden'
                );

                gpsCoordinates.innerHTML = `
                    <p class="text-xs text-blue-700">
                        ✓ GPS coordinates captured successfully.
                    </p>

                    <p class="text-[11px] text-blue-500 mt-1">
                        Accuracy: approximately ${Math.round(accuracy)} meters
                    </p>
                `;
            }


            // =================================================
            // PREPARE ADDRESS DETECTION
            // =================================================

            if (locationInput) {

                locationInput.value =
                    'Detecting address...';
            }


            if (locationIcon) {

                locationIcon.innerText =
                    '⏳';
            }


            // =================================================
            // REVERSE GEOCODING
            // =================================================

            const reverseGeocodeUrl =
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`;


            fetch(
                reverseGeocodeUrl,
                {
                    headers: {
                        'Accept-Language': 'en'
                    }
                }
            )

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


                    // =================================================
                    // ADDRESS COMPONENTS
                    // =================================================

                    const road =
                        address.road ||
                        address.pedestrian ||
                        address.highway ||
                        '';


                    const barangay =
                        address.suburb ||
                        address.village ||
                        address.quarter ||
                        address.neighbourhood ||
                        '';


                    const city =
                        address.city ||
                        address.town ||
                        address.municipality ||
                        '';


                    const province =
                        address.state ||
                        '';


                    // =================================================
                    // FORMAT ADDRESS
                    // =================================================

                    const formattedAddress = [
                        road,
                        barangay,
                        city,
                        province
                    ]
                    .filter(Boolean)
                    .join(', ');


                    if (locationInput) {

                        locationInput.value =
                            formattedAddress ||
                            'Address unavailable';
                    }


                    if (locationIcon) {

                        locationIcon.innerText =
                            '✓';
                    }


                    // =================================================
                    // DEBUG ADDRESS DATA
                    // =================================================

                    console.log(
                        'GPS Address:',
                        data.display_name
                    );

                    console.log(
                        'Road:',
                        road
                    );

                    console.log(
                        'Barangay:',
                        barangay
                    );

                    console.log(
                        'City/Municipality:',
                        city
                    );

                    console.log(
                        'Province:',
                        province
                    );

                } else {

                    if (locationInput) {

                        locationInput.value =
                            'Address unavailable';
                    }


                    if (locationIcon) {

                        locationIcon.innerText =
                            '⚠️';
                    }
                }
            })

            .catch(function (error) {

                console.error(
                    'Reverse geocoding error:',
                    error
                );


                if (locationInput) {

                    locationInput.value =
                        'Unable to get address';
                }


                if (locationIcon) {

                    locationIcon.innerText =
                        '⚠️';
                }
            });

        },


        // =================================================
        // GPS ERROR
        // =================================================

        function (error) {

            console.error(
                'GPS Error:',
                error.code,
                error.message
            );


            if (gpsBadge) {

                gpsBadge.innerText =
                    'Unavailable';

                gpsBadge.classList.remove(
                    'bg-yellow-50',
                    'text-yellow-700'
                );

                gpsBadge.classList.add(
                    'bg-red-50',
                    'text-red-700'
                );
            }


            if (locationInput) {

                locationInput.value =
                    'Unable to detect current location';
            }


            if (locationIcon) {

                locationIcon.innerText =
                    '⚠️';
            }
        },


        // =================================================
        // GPS OPTIONS
        // =================================================

        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }

    );

});