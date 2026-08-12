// =======================================================
// TRAFFICENFORCENET
// ENFORCER - ISSUE TICKET JAVASCRIPT
// =======================================================

document.addEventListener('DOMContentLoaded', function () {

    // ===================================================
    // TICKET IMAGE HANDLING
    // ===================================================

    window.openTicketCamera = function () {

        const input =
            document.getElementById('ticket_image');

        if (input) {
            input.setAttribute('capture', 'environment');
            input.click();
        }
    };


    window.openTicketFile = function () {

        const input =
            document.getElementById('ticket_image');

        if (input) {
            input.removeAttribute('capture');
            input.click();
        }
    };


    window.previewTicket = function (event) {

        const file =
            event.target.files[0];

        if (!file) {
            return;
        }

        const preview =
            document.getElementById('ticketPreview');

        const container =
            document.getElementById('ticketPreviewContainer');

        if (preview && container) {

            preview.src =
                URL.createObjectURL(file);

            container.classList.remove('hidden');
        }


        // ===================================================
        // OCR STATUS
        // ===================================================

        const ocrTitle =
            document.getElementById('ocrTitle');

        const ocrMessage =
            document.getElementById('ocrMessage');

        const ocrIcon =
            document.getElementById('ocrIcon');


        if (ocrTitle) {

            ocrTitle.innerText =
                'Ticket image ready';
        }


        if (ocrMessage) {

            ocrMessage.innerText =
                'Image uploaded. Waiting for OCR processing.';
        }


        if (ocrIcon) {

            ocrIcon.innerText =
                '✓';

            ocrIcon.classList.remove(
                'bg-blue-100'
            );

            ocrIcon.classList.add(
                'bg-green-100'
            );
        }
    };


    // ===================================================
    // VIOLATION TYPE - OTHERS
    // ===================================================

    const violationSelect =
        document.getElementById('violation_type_id');

    const otherContainer =
        document.getElementById('otherViolationContainer');


    if (
        violationSelect &&
        otherContainer
    ) {

        violationSelect.addEventListener(
            'change',
            function () {

                if (this.value === 'other') {

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
        document.getElementById('location');

    const latitudeInput =
        document.getElementById('latitude');

    const longitudeInput =
        document.getElementById('longitude');

    const gpsBadge =
        document.getElementById('gpsStatusBadge');

    const gpsCoordinates =
        document.getElementById('gpsCoordinates');

    const locationIcon =
        document.getElementById('locationIcon');


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
        // SUCCESS
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