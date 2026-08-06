@extends('layouts.enforcer')

@section('title', 'Issue Traffic Citation')

@section('content')

    @if ($errors->any())

        <div class="mx-5 mt-5 bg-red-100 border border-red-300 text-red-700 rounded-xl p-4">

            <h3 class="font-bold mb-2">
                Validation Errors:
            </h3>

            <ul class="list-disc ml-5">

                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach

            </ul>

        </div>

    @endif
    <div class="min-h-screen bg-[#F5F7FB] pb-24">


        <form action="{{ route('enforcer.violations.store') }}" method="POST" enctype="multipart/form-data">

            @csrf



            <!-- HEADER -->

            <div class="bg-gradient-to-br from-[#1D5FBF] to-[#2E77E6] text-white px-5 pt-8 pb-8 rounded-b-[30px]">


                <div class="flex items-center gap-3">


                    <a href="{{ route('enforcer.dashboard') }}"
                        class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl">

                        ←

                    </a>



                    <div>

                        <h1 class="text-xl font-bold">
                            Issue Traffic Citation
                        </h1>


                        <p class="text-blue-100 text-sm">
                            Scan official POSO citation ticket using OCR.
                        </p>


                    </div>


                </div>


            </div>





            <!-- OCR TICKET SCANNER -->

            <div class="px-5 mt-6">

                <div class="bg-white rounded-3xl shadow-md p-5">


                    <h2 class="font-bold text-gray-700 mb-4">
                        📷 Scan Citation Ticket
                    </h2>


                    <p class="text-sm text-gray-500 mb-5">
                        Capture the official POSO citation ticket or upload an existing image.
                        OCR will extract the ticket information automatically.
                    </p>



                    <!-- IMAGE PREVIEW -->

                    <div id="ticketPreviewContainer" class="hidden mb-5">

                        <img id="ticketPreview" class="w-full h-64 object-cover rounded-2xl border">

                    </div>



                    <!-- BUTTON OPTIONS -->

                    <div class="grid grid-cols-2 gap-4">


                        <!-- CAMERA BUTTON -->

                        <button type="button" onclick="openTicketCamera()"
                            class="bg-[#005FBF] text-white rounded-2xl py-4 font-bold flex flex-col items-center justify-center">


                            <span class="text-3xl">
                                📷
                            </span>


                            <span class="mt-2 text-sm">
                                Take Photo
                            </span>


                        </button>




                        <!-- FILE BUTTON -->

                        <button type="button" onclick="openTicketFile()"
                            class="bg-blue-50 text-blue-700 rounded-2xl py-4 font-bold flex flex-col items-center justify-center border border-blue-200">


                            <span class="text-3xl">
                                📁
                            </span>


                            <span class="mt-2 text-sm">
                                Upload File
                            </span>


                        </button>


                    </div>




                    <!-- HIDDEN INPUT -->

                    <input type="file" id="ticket_image" name="ticket_image" accept="image/*" capture="environment"
                        class="hidden" onchange="previewTicket(event)">



                </div>

            </div>







            <!-- OCR STATUS -->

            <div class="px-5 mt-6">

                <div class="bg-white rounded-3xl shadow-md p-5">


                    <h2 class="font-bold text-gray-700 mb-4">

                        🤖 OCR Status

                    </h2>



                    <div id="ocrStatus" class="flex items-center gap-4 bg-gray-50 rounded-2xl p-4">


                        <div id="ocrIcon"
                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-xl">

                            ⏳

                        </div>



                        <div>


                            <p id="ocrTitle" class="font-semibold text-gray-700">

                                Ready for scanning

                            </p>


                            <p id="ocrMessage" class="text-sm text-gray-500">

                                Upload a citation ticket image to begin OCR.

                            </p>


                        </div>



                    </div>


                </div>

            </div>








            <!-- OCR EXTRACTED INFORMATION -->


            <div class="px-5 mt-6">


                <div class="bg-blue-50 border border-blue-200 rounded-3xl p-5">


                    <div class="flex justify-between items-center mb-5">


                        <h2 class="font-bold text-blue-700">

                            🤖 OCR Extracted Data

                        </h2>



                        <span class="text-xs bg-blue-200 text-blue-700 px-3 py-1 rounded-full">

                            Editable

                        </span>


                    </div>




                    <div class="space-y-4">



                        <div>


                            <label class="text-sm text-gray-600">

                                Ticket Number

                            </label>


                            <input type="text" name="ticket_number" placeholder="OCR detected ticket number"
                                class="w-full mt-1 rounded-xl border-blue-300 bg-white focus:ring-blue-500">


                            <p class="text-xs text-blue-600 mt-1">

                                🤖 Detected from citation ticket

                            </p>


                        </div>





                        <div>


                            <label class="text-sm text-gray-600">

                                OCR Confidence

                            </label>


                            <input type="text" readonly value="Waiting for OCR scan"
                                class="w-full mt-1 rounded-xl bg-gray-100 border-gray-300">


                        </div>



                    </div>



                </div>


            </div>








            <!-- DRIVER INFORMATION -->


            <div class="px-5 mt-6">


                <div class="bg-white rounded-3xl shadow-md p-5">


                    <h2 class="font-bold text-gray-700 mb-5">

                        👤 Driver Information

                    </h2>




                    <div class="space-y-4">



                        <div>


                            <label class="text-sm text-gray-600">

                                First Name

                            </label>


                            <input type="text" name="first_name" placeholder="OCR detected first name"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                            <p class="text-xs text-blue-600 mt-1">

                                🤖 OCR detected • Editable

                            </p>


                        </div>






                        <div>


                            <label class="text-sm text-gray-600">

                                Middle Name

                            </label>


                            <input type="text" name="middle_name" placeholder="OCR detected middle name"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                        </div>






                        <div>


                            <label class="text-sm text-gray-600">

                                Last Name

                            </label>


                            <input type="text" name="last_name" placeholder="OCR detected last name"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                        </div>






                        <div>


                            <label class="text-sm text-gray-600">

                                Address

                            </label>


                            <input type="text" name="address" placeholder="OCR detected address"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                        </div>






                        <div>


                            <label class="text-sm text-gray-600">

                                License Number

                            </label>


                            <input type="text" name="license_number" placeholder="OCR detected license number"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                        </div>





                        <div>


                            <label class="text-sm text-gray-600">

                                Birth Date

                            </label>


                            <input type="date" name="birth_date" class="w-full mt-1 rounded-xl border-gray-300">


                        </div>





                    </div>



                </div>


            </div>
            <!-- VEHICLE INFORMATION -->


            <div class="px-5 mt-6">


                <div class="bg-white rounded-3xl shadow-md p-5">


                    <h2 class="font-bold text-gray-700 mb-5">

                        🚗 Vehicle Information

                    </h2>



                    <div class="space-y-4">



                        <div>

                            <label class="text-sm text-gray-600">
                                Plate Number
                            </label>


                            <input type="text" name="plate_number" placeholder="OCR detected plate number"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                            <p class="text-xs text-blue-600 mt-1">

                                🤖 OCR detected • Editable

                            </p>


                        </div>






                        <div>

                            <label class="text-sm text-gray-600">
                                Vehicle Type
                            </label>


                            <input type="text" name="vehicle_type" placeholder="OCR detected vehicle type"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                        </div>






                        <div>

                            <label class="text-sm text-gray-600">
                                Region Number
                            </label>


                            <input type="text" name="region_number" placeholder="OCR detected region number"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                        </div>






                        <div>

                            <label class="text-sm text-gray-600">
                                Vehicle Owner
                            </label>


                            <input type="text" name="owner_name" placeholder="OCR detected owner name"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                        </div>



                    </div>


                </div>


            </div>








            <!-- VIOLATION INFORMATION -->


            <div class="px-5 mt-6">


                <div class="bg-white rounded-3xl shadow-md p-5">


                    <h2 class="font-bold text-gray-700 mb-5">

                        ⚠️ Violation Information

                    </h2>




                    <div class="space-y-4">





                        <div>

                            <label class="text-sm text-gray-600">
                                Violation Type
                            </label>



                            <select name="violation_type_id" id="violation_type_id"
                                class="w-full mt-1 rounded-xl bg-blue-50 border-blue-200">


                                <option value="" selected disabled>
                                    Select violation type
                                </option>



                                @foreach ($violationTypes as $type)
                                    <option value="{{ $type->id }}">

                                        {{ $type->name }}

                                    </option>
                                @endforeach



                                <option value="other">

                                    Others

                                </option>



                            </select>



                            <p class="text-xs text-blue-600 mt-1">

                                🤖 OCR detected • Editable

                            </p>



                        </div>






                        <div id="otherViolationContainer" class="hidden">


                            <label class="text-sm text-gray-600">

                                Specify Violation

                            </label>



                            <input type="text" name="other_violation" placeholder="Enter violation"
                                class="w-full mt-1 rounded-xl border-gray-300">


                        </div>







                        <div>


                            <label class="text-sm text-gray-600">

                                Issued By

                            </label>



                            <input type="text" readonly value="{{ auth()->user()->name }}"
                                class="w-full mt-1 rounded-xl bg-gray-100 border-gray-300">


                        </div>







                        <div>


                            <label class="text-sm text-gray-600">

                                Remarks

                            </label>



                            <textarea name="remarks" rows="4" placeholder="OCR extracted remarks or additional notes"
                                class="w-full mt-1 rounded-xl border-gray-300"></textarea>



                        </div>



                    </div>



                </div>


            </div>




            <!-- EVIDENCE PHOTO -->

            <div class="px-5 mt-6">


                <div class="bg-white rounded-3xl shadow-md p-5">


                    <h2 class="font-bold text-gray-700 mb-4">

                        📸 Violation Evidence

                    </h2>



                    <label
                        class="border-2 border-dashed border-gray-300 rounded-3xl h-64 flex flex-col items-center justify-center cursor-pointer hover:bg-blue-50 transition">


                        <div class="text-6xl">

                            📷

                        </div>



                        <h3 class="font-bold text-lg mt-4">

                            Capture Evidence Photo

                        </h3>



                        <p class="text-gray-500 text-sm text-center px-5 mt-2">

                            Take a photo showing the actual traffic violation.

                        </p>



                        <input type="file" name="evidence_images[]" accept="image/*" capture="environment" multiple
                            class="hidden">


                    </label>



                    <div class="mt-4 bg-gray-50 rounded-xl p-3">


                        <p class="text-sm text-gray-600">

                            Recommended evidence:

                        </p>


                        <ul class="text-sm text-gray-500 mt-2 space-y-1">


                            <li>✓ Vehicle involved</li>

                            <li>✓ Traffic violation scene</li>

                            <li>✓ Road/location condition</li>


                        </ul>


                    </div>



                </div>


            </div>



            <!-- GPS LOCATION -->

            <div class="px-5 mt-6">

                <div class="bg-white rounded-3xl shadow-md p-5">


                    <h2 class="font-bold text-gray-700 mb-5">

                        📍 Current Location

                    </h2>


                    <p class="text-sm text-gray-500 mb-4">

                        Location is automatically captured from the device GPS.

                    </p>



                    <!-- Visible Location -->

                    <div>

                        <label class="text-sm text-gray-600">

                            Address

                        </label>


                        <input type="text" id="location" name="location" readonly
                            placeholder="Detecting current location..."
                            class="w-full mt-1 rounded-xl bg-gray-100 border-gray-300">


                    </div>



                    <!-- Hidden GPS Data -->

                    <input type="hidden" name="latitude" id="latitude">



                    <input type="hidden" name="longitude" id="longitude">



                </div>

            </div>








            <!-- DATE AND TIME -->


            <div class="px-5 mt-6">


                <div class="bg-white rounded-3xl shadow-md p-5">


                    <h2 class="font-bold text-gray-700">

                        📅 Citation Date & Time

                    </h2>



                    <p class="text-sm text-gray-500 mb-4">

                        Automatically recorded by the system.

                    </p>





                    <div class="grid grid-cols-2 gap-4">



                        <div>


                            <label class="text-sm text-gray-600">

                                Date

                            </label>


                            <input type="text" readonly
                                value="{{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}"
                                class="w-full mt-1 rounded-xl bg-gray-100 border-gray-300">


                        </div>







                        <div>


                            <label class="text-sm text-gray-600">

                                Time

                            </label>


                            <input type="text" readonly
                                value="{{ now()->setTimezone('Asia/Manila')->format('h:i A') }}"
                                class="w-full mt-1 rounded-xl bg-gray-100 border-gray-300">


                        </div>



                    </div>



                </div>


            </div>









            <!-- SUBMIT -->


            <div class="px-5 mt-8 mb-10">


                <button type="submit"
                    class="w-full bg-[#005FBF] hover:bg-blue-700 text-white rounded-2xl py-4 font-bold text-lg shadow-lg">


                    ✓ Review & Submit Citation


                </button>


            </div>





        </form>


    </div>







    <script>
        // ===============================
        // TICKET IMAGE HANDLING FUNCTIONS
        // ===============================

        function openTicketCamera() {

            const input = document.getElementById('ticket_image');

            if (input) {

                input.setAttribute('capture', 'environment');

                input.click();

            }

        }



        function openTicketFile() {

            const input = document.getElementById('ticket_image');

            if (input) {

                input.removeAttribute('capture');

                input.click();

            }

        }



        function previewTicket(event) {

            const file = event.target.files[0];


            if (file) {


                const preview = document.getElementById('ticketPreview');

                const container = document.getElementById('ticketPreviewContainer');


                if (preview && container) {

                    preview.src = URL.createObjectURL(file);

                    container.classList.remove('hidden');

                }



                const ocrTitle = document.getElementById('ocrTitle');

                const ocrMessage = document.getElementById('ocrMessage');

                const ocrIcon = document.getElementById('ocrIcon');



                if (ocrTitle) {

                    ocrTitle.innerText = "Ticket image ready";

                }



                if (ocrMessage) {

                    ocrMessage.innerText =
                        "Image uploaded. Waiting for OCR processing.";

                }



                if (ocrIcon) {

                    ocrIcon.innerText = "✓";

                }


            }

        }





        // ===============================
        // PAGE INITIALIZATION
        // ===============================

        document.addEventListener('DOMContentLoaded', function() {



            // ===============================
            // OTHER VIOLATION TOGGLE
            // ===============================


            const violationSelect =
                document.getElementById('violation_type_id');


            const otherContainer =
                document.getElementById('otherViolationContainer');



            if (violationSelect && otherContainer) {


                violationSelect.addEventListener('change', function() {


                    if (this.value === 'other') {


                        otherContainer.classList.remove('hidden');


                    } else {


                        otherContainer.classList.add('hidden');


                    }


                });


            }





            // ===============================
            // GPS CAPTURE
            // ===============================


            if (navigator.geolocation) {



                navigator.geolocation.getCurrentPosition(function(position) {



                    const latitude =
                        position.coords.latitude;


                    const longitude =
                        position.coords.longitude;



                    const latitudeInput =
                        document.getElementById('latitude');


                    const longitudeInput =
                        document.getElementById('longitude');


                    const locationInput =
                        document.getElementById('location');




                    // Save GPS silently

                    if (latitudeInput) {

                        latitudeInput.value = latitude;

                    }



                    if (longitudeInput) {

                        longitudeInput.value = longitude;

                    }





                    // Reverse Geocoding

                    if (locationInput) {


                        locationInput.value =
                            "Detecting address...";



                        fetch(
                                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`, {
                                    headers: {
                                        'Accept-Language': 'en'
                                    }
                                }
                            )

                            .then(response => response.json())

                            .then(data => {



                                if (data && data.address) {


                                    let address = data.address;



                                    let formattedAddress = [

                                            address.road,

                                            address.suburb ||
                                            address.village,

                                            address.city ||
                                            address.town ||
                                            address.municipality,

                                            address.state


                                        ]
                                        .filter(Boolean)
                                        .join(', ');



                                    locationInput.value =
                                        formattedAddress;



                                } else {


                                    locationInput.value =
                                        "Address unavailable";


                                }



                            })

                            .catch(error => {


                                console.log(
                                    "Reverse geocoding error:",
                                    error
                                );


                                locationInput.value =
                                    "Unable to get address";


                            });



                    }



                }, function(error) {



                    console.log(
                        "GPS Error:",
                        error.message
                    );



                    const locationInput =
                        document.getElementById('location');



                    if (locationInput) {

                        locationInput.value =
                            "Unable to detect current location";

                    }



                });



            } else {



                const locationInput =
                    document.getElementById('location');



                if (locationInput) {

                    locationInput.value =
                        "GPS is not supported on this device";

                }


            }



        });
    </script>

@endsection
