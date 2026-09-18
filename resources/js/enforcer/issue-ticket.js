// =======================================================
// TRAFFICENFORCENET
// ENFORCER - ISSUE TICKET JAVASCRIPT
// =======================================================

// =======================================================
// OFFLINE / ONLINE CONNECTION STATUS
// =======================================================

function updateConnectionStatus() {

    const status =
        document.getElementById('connectionStatus');

    const icon =
        document.getElementById('connectionStatusIcon');

    const text =
        document.getElementById('connectionStatusText');

    if (!status || !icon || !text) {
        return;
    }

    if (navigator.onLine) {

        status.className =
            'bg-green-50 border border-green-200 text-green-700 rounded-2xl px-4 py-3 text-sm font-medium flex items-center gap-2';

        icon.textContent = '●';
        text.textContent = 'Online - Data can be synchronized';

    } else {

        status.className =
            'bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-2xl px-4 py-3 text-sm font-medium flex items-center gap-2';

        icon.textContent = '●';
        text.textContent = 'Offline - Data will be saved for synchronization';
    }
}

window.addEventListener(
    'online',
    updateConnectionStatus
);

window.addEventListener(
    'offline',
    updateConnectionStatus
);

document.addEventListener(
    'DOMContentLoaded',
    updateConnectionStatus
);


// =======================================================
// MAIN PAGE FUNCTIONS
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


// =======================================================
// TRAFFICENFORCENET - OFFLINE TICKET STORAGE
// =======================================================

const OFFLINE_DB_NAME =
    'TrafficEnforceNetDB';

const OFFLINE_DB_VERSION =
    1;

const OFFLINE_STORE =
    'pendingTickets';


// =======================================================
// OPEN INDEXEDDB
// =======================================================

function openOfflineDatabase() {

    return new Promise((resolve, reject) => {

        const request =
            indexedDB.open(
                OFFLINE_DB_NAME,
                OFFLINE_DB_VERSION
            );


        request.onupgradeneeded =
            function (event) {

                const db =
                    event.target.result;


                if (
                    !db.objectStoreNames.contains(
                        OFFLINE_STORE
                    )
                ) {

                    const store =
                        db.createObjectStore(
                            OFFLINE_STORE,
                            {
                                keyPath: 'local_id'
                            }
                        );


                    store.createIndex(
                        'created_at',
                        'created_at',
                        {
                            unique: false
                        }
                    );


                    console.log(
                        'IndexedDB object store created:',
                        OFFLINE_STORE
                    );
                }
            };


        request.onsuccess =
            function (event) {

                const db =
                    event.target.result;

                console.log(
                    'IndexedDB opened successfully:',
                    OFFLINE_DB_NAME
                );

                resolve(db);
            };


        request.onerror =
            function (event) {

                console.error(
                    'IndexedDB error:',
                    event.target.error
                );

                reject(
                    event.target.error
                );
            };
    });
}


// =======================================================
// INITIALIZE INDEXEDDB
// =======================================================

document.addEventListener(
    'DOMContentLoaded',
    async function () {

        try {

            const db =
                await openOfflineDatabase();

            console.log(
                'TrafficEnforceNet IndexedDB is ready.'
            );

            console.log(
                'Database:',
                db.name
            );

            console.log(
                'Object stores:',
                Array.from(
                    db.objectStoreNames
                )
            );

            db.close();

        } catch (error) {

            console.error(
                'Unable to initialize IndexedDB:',
                error
            );
        }
    }
);


// =======================================================
// SAVE TICKET OFFLINE
// =======================================================

async function saveTicketOffline(form) {

    try {

        const db =
            await openOfflineDatabase();

        const formData =
            new FormData(form);

        const ticket = {

            local_id:
                crypto.randomUUID(),

            created_at:
                new Date().toISOString(),

            sync_status:
                'pending',

            data: {},

            ticket_image:
                null,

            evidence_images:
                []
        };


        // ---------------------------------------------------
        // SAVE NORMAL FORM DATA
        // ---------------------------------------------------

        for (
            const [key, value]
            of formData.entries()
        ) {

            // Do not store uploaded files here.
            if (value instanceof File) {

                continue;
            }

            // Do not store CSRF token.
            if (key === '_token') {

                continue;
            }

            ticket.data[key] =
                value;
        }


        // ---------------------------------------------------
        // SAVE CITATION TICKET IMAGE
        // ---------------------------------------------------

        const ticketInput =
            document.getElementById(
                'ticket_image'
            );


        if (
            ticketInput &&
            ticketInput.files &&
            ticketInput.files.length > 0
        ) {

            const imageFile =
                ticketInput.files[0];

            ticket.ticket_image =
                imageFile;

            console.log(
                'Offline ticket image captured:',
                imageFile.name,
                imageFile.type,
                imageFile.size
            );
        }


        // ---------------------------------------------------
        // SAVE EVIDENCE IMAGES
        // ---------------------------------------------------

        const evidenceInput =
            form.querySelector(
                'input[name="evidence_images[]"]'
            );


        if (
            evidenceInput &&
            evidenceInput.files
        ) {

            for (
                const file
                of evidenceInput.files
            ) {

                ticket.evidence_images.push(
                    file
                );

                console.log(
                    'Offline evidence image captured:',
                    file.name,
                    file.type,
                    file.size
                );
            }
        }


        // ---------------------------------------------------
        // STORE IN INDEXEDDB
        // ---------------------------------------------------

        await new Promise(
            (resolve, reject) => {

                const transaction =
                    db.transaction(
                        OFFLINE_STORE,
                        'readwrite'
                    );


                const store =
                    transaction.objectStore(
                        OFFLINE_STORE
                    );


                const request =
                    store.put(ticket);


                request.onsuccess =
                    function () {

                        console.log(
                            'Offline ticket stored:',
                            ticket.local_id
                        );

                        resolve();
                    };


                request.onerror =
                    function () {

                        console.error(
                            'Failed to store offline ticket:',
                            request.error
                        );

                        reject(
                            request.error
                        );
                    };
            }
        );


        db.close();

        return ticket.local_id;

    } catch (error) {

        console.error(
            'Offline ticket storage error:',
            error
        );

        throw error;
    }
}


// =======================================================
// GET PENDING TICKETS
// =======================================================

async function getPendingTickets() {

    const db =
        await openOfflineDatabase();


    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    OFFLINE_STORE,
                    'readonly'
                );


            const store =
                transaction.objectStore(
                    OFFLINE_STORE
                );


            const request =
                store.getAll();


            request.onsuccess =
                function () {

                    db.close();

                    resolve(
                        request.result
                    );
                };


            request.onerror =
                function () {

                    db.close();

                    reject(
                        request.error
                    );
                };
        }
    );
}


// =======================================================
// DELETE SYNCED TICKET
// =======================================================

async function deletePendingTicket(localId) {

    const db =
        await openOfflineDatabase();


    return new Promise(
        (resolve, reject) => {

            const transaction =
                db.transaction(
                    OFFLINE_STORE,
                    'readwrite'
                );


            const store =
                transaction.objectStore(
                    OFFLINE_STORE
                );


            const request =
                store.delete(localId);


            request.onsuccess =
                function () {

                    db.close();

                    resolve();
                };


            request.onerror =
                function () {

                    db.close();

                    reject(
                        request.error
                    );
                };
        }
    );
}


// =======================================================
// BUILD FORMDATA FOR SYNCHRONIZATION
// =======================================================

function buildSyncFormData(ticket) {

    const formData =
        new FormData();


    // ---------------------------------------------------
    // RESTORE NORMAL FORM DATA
    // ---------------------------------------------------

    Object.entries(
        ticket.data || {}
    ).forEach(
        function ([key, value]) {

            formData.append(
                key,
                value ?? ''
            );
        }
    );


    // ---------------------------------------------------
    // RESTORE TICKET IMAGE
    // ---------------------------------------------------

    if (
        ticket.ticket_image
    ) {

        formData.append(
            'ticket_image',
            ticket.ticket_image,
            ticket.ticket_image.name ||
            'ticket-image'
        );
    }


    // ---------------------------------------------------
    // RESTORE EVIDENCE IMAGES
    // ---------------------------------------------------

    if (
        Array.isArray(
            ticket.evidence_images
        )
    ) {

        ticket.evidence_images.forEach(
            function (file) {

                if (file) {

                    formData.append(
                        'evidence_images[]',
                        file,
                        file.name ||
                        'evidence-image'
                    );
                }
            }
        );
    }


    return formData;
}


// =======================================================
// GET CSRF TOKEN FROM CURRENT PAGE
// =======================================================

function getCsrfToken() {

    const tokenInput =
        document.querySelector(
            'input[name="_token"]'
        );


    if (
        tokenInput &&
        tokenInput.value
    ) {

        return tokenInput.value;
    }


    const metaToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        );


    if (
        metaToken
    ) {

        return metaToken.getAttribute(
            'content'
        );
    }


    return null;
}


// =======================================================
// SYNCHRONIZE ONE OFFLINE TICKET
// =======================================================

async function syncOneTicket(ticket) {

    console.log(
        'Starting synchronization:',
        ticket.local_id
    );


    const formData =
        buildSyncFormData(ticket);


    const csrfToken =
        getCsrfToken();


    if (!csrfToken) {

        throw new Error(
            'CSRF token was not found on the page.'
        );
    }


    // ---------------------------------------------------
    // ADD CSRF TOKEN ONLY WHEN SENDING
    // ---------------------------------------------------

    formData.append(
        '_token',
        csrfToken
    );


    // ---------------------------------------------------
    // SEND TO EXISTING LARAVEL STORE ROUTE
    // ---------------------------------------------------

    const response =
        await fetch(
            '/enforcer/issue-ticket',
            {
                method: 'POST',

                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },

                body: formData,

                credentials: 'same-origin'
            }
        );


    // ---------------------------------------------------
    // CHECK HTTP RESPONSE
    // ---------------------------------------------------

    if (!response.ok) {

        let errorMessage =
            `HTTP ${response.status}`;

        try {

            const errorData =
                await response.json();

            if (
                errorData.message
            ) {

                errorMessage +=
                    ` - ${errorData.message}`;
            }

        } catch (error) {

            // Response was not JSON.
        }


        throw new Error(
            errorMessage
        );
    }


    // ---------------------------------------------------
    // SYNCHRONIZATION SUCCESS
    // ---------------------------------------------------

    console.log(
        'Offline ticket synchronized successfully:',
        ticket.local_id
    );


    return true;
}


// =======================================================
// SYNCHRONIZE ALL PENDING TICKETS
// =======================================================

async function syncPendingTickets() {

    // ---------------------------------------------------
    // DO NOT RUN WHILE OFFLINE
    // ---------------------------------------------------

    if (!navigator.onLine) {

        console.log(
            'Synchronization skipped: device is offline.'
        );

        return;
    }


    try {

        const tickets =
            await getPendingTickets();


        if (
            tickets.length === 0
        ) {

            console.log(
                'No pending offline tickets to synchronize.'
            );

            return;
        }


        console.log(
            `Found ${tickets.length} pending offline ticket(s).`
        );


        // -------------------------------------------------
        // SYNCHRONIZE EACH TICKET
        // -------------------------------------------------

        for (
            const ticket
            of tickets
        ) {

            try {

                await syncOneTicket(
                    ticket
                );


                // -----------------------------------------
                // DELETE ONLY AFTER SUCCESS
                // -----------------------------------------

                await deletePendingTicket(
                    ticket.local_id
                );


                console.log(
                    'Removed synchronized ticket from IndexedDB:',
                    ticket.local_id
                );

            } catch (error) {

                console.error(
                    'Synchronization failed for ticket:',
                    ticket.local_id,
                    error
                );


                // -----------------------------------------
                // STOP HERE
                // -----------------------------------------
                //
                // Keep the ticket in IndexedDB.
                // It can be synchronized again later.
                //

                break;
            }
        }


        await updatePendingSyncCount();


    } catch (error) {

        console.error(
            'Offline synchronization error:',
            error
        );
    }
}


// =======================================================
// ONLINE EVENT - START AUTOMATIC SYNCHRONIZATION
// =======================================================

window.addEventListener(
    'online',
    async function () {

        console.log(
            'Internet connection restored.'
        );


        updateConnectionStatus();


        // Give the connection a short moment
        // to stabilize before synchronization.

        setTimeout(
            async function () {

                await syncPendingTickets();

            },
            1500
        );
    }
);


// =======================================================
// OFFLINE FORM SUBMISSION
// =======================================================

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const form =
            document.querySelector(
                'form[action*="issue-ticket"]'
            );


        if (!form) {

            console.warn(
                'Issue Ticket form was not found.'
            );

            return;
        }


        form.addEventListener(
            'submit',
            async function (event) {

                // -----------------------------------------
                // IF ONLINE
                // -----------------------------------------

                if (navigator.onLine) {

                    return;
                }


                // -----------------------------------------
                // STOP NORMAL SUBMISSION
                // -----------------------------------------

                event.preventDefault();


                try {

                    const localId =
                        await saveTicketOffline(
                            form
                        );


                    console.log(
                        'Ticket saved offline:',
                        localId
                    );


                    alert(
                        'No internet connection.\n\n' +
                        'The citation has been saved on this device ' +
                        'and will be synchronized automatically when ' +
                        'the connection returns.'
                    );


                } catch (error) {

                    console.error(
                        'Unable to save ticket offline:',
                        error
                    );


                    alert(
                        'Unable to save the citation offline. ' +
                        'Please try again.'
                    );
                }

            }
        );

    }
);


// =======================================================
// SHOW PENDING COUNT
// =======================================================

async function updatePendingSyncCount() {

    try {

        const tickets =
            await getPendingTickets();


        console.log(
            'Pending offline tickets:',
            tickets.length
        );

    } catch (error) {

        console.error(
            'Unable to check pending tickets:',
            error
        );
    }
}


// =======================================================
// INITIALIZE OFFLINE STORAGE
// =======================================================

document.addEventListener(
    'DOMContentLoaded',
    updatePendingSyncCount
);


// =======================================================
// TRY SYNCHRONIZATION WHEN PAGE LOADS ONLINE
// =======================================================

document.addEventListener(
    'DOMContentLoaded',
    async function () {

        if (navigator.onLine) {

            console.log(
                'Page loaded while online. Checking pending tickets...'
            );

            await syncPendingTickets();
        }
    }
);