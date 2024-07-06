<!DOCTYPE html>
<html lang='es'>

<head>
    <?php require_once('../Html/head.php') ?>
    <link href='../../public/lib/calendar/lib/main.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .custom-flatpickr {
            display: flex;
            align-items: center;
        }

        .custom-flatpickr input {
            margin-right: 5px;
            flex: 1;
        }
    </style>
    <script type="text/javascript">
        /* exported gapiLoaded */
        /* exported gisLoaded */
        /* exported handleAuthClick */
        /* exported handleSignoutClick */

        // TODO(developer): Set to client ID and API key from the Developer Console
        const CLIENT_ID = '77231905307-7k8dj6k23khpo32b95r8qdqljbfstpcv.apps.googleusercontent.com';
        const API_KEY = 'AIzaSyB3r8pkADS-SnvvL1ShswEFuAu53ptbH1w';

        // Discovery doc URL for APIs used by the quickstart
        const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest';

        // Authorization scopes required by the API; multiple scopes can be
        // included, separated by spaces.
        const SCOPES = 'https://www.googleapis.com/auth/calendar.readonly';

        let tokenClient;
        let gapiInited = false;
        let gisInited = false;

        //document.getElementById('authorize_button').style.visibility = 'visible';
        //document.getElementById('signout_button').style.visibility = 'visible';

        /**
         * Callback after api.js is loaded.
         */
        function gapiLoaded() {
            gapi.load('client', initializeGapiClient);
        }

        /**
         * Callback after the API client is loaded. Loads the
         * discovery doc to initialize the API.
         */
        async function initializeGapiClient() {
            await gapi.client.init({
                apiKey: API_KEY,
                discoveryDocs: [DISCOVERY_DOC],
            });
            gapiInited = true;
            maybeEnableButtons();
        }

        /**
         * Callback after Google Identity Services are loaded.
         */
        function gisLoaded() {
            tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: CLIENT_ID,
                scope: SCOPES,
                callback: '', // defined later
            });
            gisInited = true;
            maybeEnableButtons();
        }

        /**
         * Enables user interaction after all libraries are loaded.
         */
        function maybeEnableButtons() {
            if (gapiInited && gisInited) {
                document.getElementById('authorize_button').style.visibility = 'visible';
            }
        }

        /**
         *  Sign in the user upon button click.
         */
        function handleAuthClick() {
            tokenClient.callback = async (resp) => {
                if (resp.error !== undefined) {
                    throw (resp);
                }
                document.getElementById('signout_button').style.visibility = 'visible';
                document.getElementById('authorize_button').innerText = 'Refresh';
                await listUpcomingEvents();
            };

            if (gapi.client.getToken() === null) {
                // Prompt the user to select a Google Account and ask for consent to share their data
                // when establishing a new session.
                tokenClient.requestAccessToken({
                    prompt: 'consent'
                });
            } else {
                // Skip display of account chooser and consent dialog for an existing session.
                tokenClient.requestAccessToken({
                    prompt: ''
                });
            }
        }

        /**
         *  Sign out the user upon button click.
         */
        function handleSignoutClick() {
            const token = gapi.client.getToken();
            if (token !== null) {
                google.accounts.oauth2.revoke(token.access_token);
                gapi.client.setToken('');
                document.getElementById('content').innerText = '';
                document.getElementById('authorize_button').innerText = 'Authorize';
                document.getElementById('signout_button').style.visibility = 'visible';
            }
        }

        /**
         * Print the summary and start datetime/date of the next ten events in
         * the authorized user's calendar. If no events are found an
         * appropriate message is printed.
         */
        async function listUpcomingEvents() {
            let response;
            try {
                const request = {
                    'calendarId': 'primary',
                    'timeMin': (new Date()).toISOString(),
                    'showDeleted': false,
                    'singleEvents': true,
                    'maxResults': 10,
                    'orderBy': 'startTime',
                };
                response = await gapi.client.calendar.events.list(request);
            } catch (err) {
                document.getElementById('content').innerText = err.message;
                return;
            }

            const events = response.result.items;
            if (!events || events.length == 0) {
                document.getElementById('content').innerText = 'No events found.';
                return;
            }
            // Flatten to string to display
            const output = events.reduce(
                (str, event) => `${str}${event.summary} (${event.start.dateTime || event.start.date})\n`,
                'Events:\n');
            document.getElementById('content').innerText = output;
        }
    </script>
    <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
    <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>

</head>

<body>
    <div class='container-xxl position-relative bg-white d-flex p-0'>
        <!-- Spinner Start -->
        <div id='spinner' class='show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center'>
            <div class='spinner-border text-primary' style='width: 3rem; height: 3rem;' role='status'>
                <span class='sr-only'>Cargando...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <?php require_once('../Html/menu.php') ?>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class='content'>
            <!-- Navbar Start -->
            <?php require_once('../Html/header.php') ?>
            <!-- Navbar End -->


            <!-- Sale & Revenue Start
            <div class='container-fluid pt-4 px-4'>
                <div class='row g-4'>
                    <div class='col-sm-6 col-xl-3'>
                        <div class='bg-light rounded d-flex align-items-center justify-content-between p-4'>
                            <i class='fa fa-chart-line fa-3x text-primary'></i>
                            <div class='ms-3'>
                                <p class='mb-2'>Today Sale</p>
                                <h6 class='mb-0'>$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class='col-sm-6 col-xl-3'>
                        <div class='bg-light rounded d-flex align-items-center justify-content-between p-4'>
                            <i class='fa fa-chart-bar fa-3x text-primary'></i>
                            <div class='ms-3'>
                                <p class='mb-2'>Total Sale</p>
                                <h6 class='mb-0'>$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class='col-sm-6 col-xl-3'>
                        <div class='bg-light rounded d-flex align-items-center justify-content-between p-4'>
                            <i class='fa fa-chart-area fa-3x text-primary'></i>
                            <div class='ms-3'>
                                <p class='mb-2'>Today Revenue</p>
                                <h6 class='mb-0'>$1234</h6>
                            </div>
                        </div>
                    </div>
                    <div class='col-sm-6 col-xl-3'>
                        <div class='bg-light rounded d-flex align-items-center justify-content-between p-4'>
                            <i class='fa fa-chart-pie fa-3x text-primary'></i>
                            <div class='ms-3'>
                                <p class='mb-2'>Total Revenue</p>
                                <h6 class='mb-0'>$1234</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             Sale & Revenue End -->


            <!-- Sales Chart Start
            <div class='container-fluid pt-4 px-4'>
                <div class='row g-4'>
                    <div class='col-sm-12 col-xl-6'>
                        <div class='bg-light text-center rounded p-4'>
                            <div class='d-flex align-items-center justify-content-between mb-4'>
                                <h6 class='mb-0'>Worldwide Sales</h6>
                                <a href=''>Show All</a>
                            </div>
                            <canvas id='worldwide-sales'></canvas>
                        </div>
                    </div>
                    <div class='col-sm-12 col-xl-6'>
                        <div class='bg-light text-center rounded p-4'>
                            <div class='d-flex align-items-center justify-content-between mb-4'>
                                <h6 class='mb-0'>Salse & Revenue</h6>
                                <a href=''>Show All</a>
                            </div>
                            <canvas id='salse-revenue'></canvas>
                        </div>
                    </div>
                </div>
            </div>
           Sales Chart End -->


            <!-- Recent Sales Start -->
            <div class='container-fluid pt-4 px-4'>
                <div class='d-flex align-items-center justify-content-between mb-4'>
                    <br>
                    <h6 class='mb-0'> </h6>

                    <button onclick="nuevo()" class='btn btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalCitasNuevo'>
                        Nueva Cita
                    </button>
                    <button onclick="nuevoSucursalPaciente(); nuevoEspecialidadesPaciente(); nuevoPacientesCombo();nuevoSeguro()" class='btn btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalPacientes'>
                        Nuevo Paciente
                    </button>
                </div>
                <!--<div id='calendar'></div>-->
            </div>
            <!-- Recent Sales End -->

            <!-- Widgets Start -->
            <!--Add buttons to initiate auth sequence and sign out-->
            <button id="authorize_button" onclick="handleAuthClick()">Authorize</button>
            <button id="signout_button" onclick="handleSignoutClick()">Sign Out</button>

            <pre id="content" style="white-space: pre-wrap;"></pre>

            <!-- Widgets End -->


            <!-- Footer Start -->
            <?php require_once('../Html/footer.php') ?>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href='#' class='btn btn-lg btn-primary btn-lg-square back-to-top'><i class='bi bi-arrow-up'></i></a>
    </div>

    <!-- Start Modal -->
    <div class='modal fade' id='modalCitasNuevo' data-bs-backdrop="static" data-bs-keyboard="false" tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>

                <div class='modal-header'>
                    <h5 class='modal-title' id='titleLabelCitas'>Nueva Citas</h5>
                    <button type='button' onclick='limpiacajas()' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    <br />

                </div>
                <form method='post' id='Citas_form'>
                    <div class='modal-body'>
                        <input type='hidden' name='idCitas' id='idCitas' />
                        <div class='row mb-3'>




                            <label class='form-control-label' for='Fecha'>Fecha</label>

                            <div class='col-sm-10'>

                                <input type="text" id="fecha" name="fecha" class="form-control">



                                <!-- <input type='datetime-local' onfocusout="validarFechaHora()" required class='form-control' name='Fecha' id='Fecha' placeholder='Ingrese elFecha' onfocus="(this.type='datetime-local')" onblur="(this.type='text')" oninput="agruparMinutos()" />
                                    <br>-->
                                <p id="mensajeError" style="color: red;"></p>
                            </div>
                        </div>

                        <div class='row mb-3'>

                            <h6 class='mb-4'>Pacientes</h6>
                            <select name='combo_idPacientes' style="width: 75%" id='combo_idPacientes' class="js-example-basic-single">
                                <option selected>Seleccione una opción</option>
                            </select>
                        </div>
                        <div class='row mb-3'>
                            <h6 class='mb-4'>Sucursal</h6>
                            <select name='combo_SucursalId' id='combo_SucursalId' style="width: 75%" class="js-example-basic-single">
                                <option selected>Seleccione una opción</option>
                            </select>
                        </div>
                        <div class='row mb-3'>
                            <h6 class='mb-4'>Especialidades</h6>
                            <select name='combo_idEspecialidades' id='combo_idEspecialidades' style="width: 75%" class="js-example-basic-single">
                                <option selected>Seleccione una opción</option>
                            </select>
                        </div>
                        <div class='row mb-3'>
                            <h6 class='mb-4'>Seguro Médico</h6>
                            <select name='idSeguro' id='idSeguro' class="js-example-basic-single" style="width: 75%">
                                <option selected>Seleccione una opción</option>
                            </select>
                        </div>
                        <div class='row mb-3'>
                            <label class='form-control-label' for='Motivo'>Motivo</label>
                            <div class='col-sm-10'>
                                <textarea type='text' class='form-control' name='Motivo' id='Motivo' placeholder='Ingrese el motivo de la consulta'></textarea>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' name='action' value='add' class='btn btn-primary'> Guardar</button>
                        <button type='button' class='btn btn-dark' data-bs-dismiss='modal' onclick='limpiacajas()'>Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class='modal fade' id='modalPacientes' data-bs-backdrop="static" data-bs-keyboard="false" tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>

                <div class='modal-header'>
                    <h5 class='modal-title' id='titleLabelPacientes'>Nuevo Pacientes</h5>
                    <button type='button' onclick='limpiacajas()' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <form method='post' id='Pacientes_form'>
                    <div class='modal-body'>

                        <input type='hidden' name='combo_idPacientesCita' id='combo_idPacientesCita' />
                        <div class='row mb-3'>
                            <label class='form-control-label fw-bold' for='Cedula'>Cédula o Passaporte</label>
                            <select name='cedula' id='cedula' onchange="llenaNombres(this)" required class='form-select'>
                                <option selected>Seleccione una opción</option>
                            </select>
                        </div>
                        <div class='row mb-3'>
                            <label class='form-control-label fw-bold' for='Nombres'>Nombres</label>
                            <div class='col-sm-10'>
                                <input type='text' required class='form-control' name='Nombres' id='Nombres' placeholder='Ingrese los Nombres' />
                            </div>
                        </div>
                        <div class='row mb-3'>
                            <label class='form-control-label fw-bold' for='Apellidos'>Apellidos</label>
                            <div class='col-sm-10'>
                                <input type='text' required class='form-control' name='Apellidos' id='Apellidos' placeholder='Ingrese los Apellidos' />
                            </div>
                        </div>
                        <div class='row mb-3'>
                            <label class='form-control-label' for='Fecha'>Fecha</label>
                            <div class='col-sm-10'>
                                <input type='datetime-local' onfocusout="validarFechaHora()" required class='form-control' name='Fecha' id='Fecha' placeholder='Ingrese elFecha' />
                                <br>
                                <p id="mensajeError" style="color: red;"></p>
                            </div>
                        </div>
                        <div class='row mb-3'>
                            <h6 class='mb-4'>Sucursal</h6>
                            <select name='SucursalId' id='SucursalId' required class='form-select'>
                                <option selected>Seleccione una opción</option>
                            </select>
                        </div>
                        <div class='row mb-3'>
                            <h6 class='mb-4 fw-bold'>Especialidades</h6>
                            <select name='idEspecialidades' id='idEspecialidades' required class='form-select'>
                                <option selected>Seleccione una opción</option>
                            </select>
                        </div>
                        <div class='row mb-3'>
                            <h6 class='mb-4 fw-bold'>Seguro Médico</h6>
                            <select name='idSeguroPaciente' id='idSeguroPaciente' required class='form-select'>
                                <option selected>Seleccione una opción</option>
                            </select>
                        </div>
                        <div class='row mb-3 fw-bold'>
                            <label class='form-control-label' for='Motivo'>Motivo</label>
                            <div class='col-sm-10'>
                                <textarea type='text' class='form-control' name='MotivoCita' id='MotivoCita' placeholder='Ingrese elMotivo'></textarea>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' name='action' value='add' class='btn btn-primary'> Guardar</button>
                        <button type='button' class='btn btn-dark' data-bs-dismiss='modal' onclick='limpiacajas()'>Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="modalCitas" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Información Cita</h5>
                    <button type="button" onclick="limpia()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type='hidden' name='idCitas' id='idCitas' />
                    <table class="table table-bordered">
                        <tbody id="tablacita"></tbody>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="EliminarCita()" data-bs-dismiss="modal">Eliminar Cita</button>
                    <button type="button" class="btn btn-success" onclick="EditarCita()" data-bs-dismiss="modal">Editar Cita</button>
                    <button type="button" class="btn btn-primary" onclick="EvolucionCita()" data-bs-dismiss="modal">Evolucionar Cita</button>
                    <button type="button" class="btn btn-secondary" onclick="limpia()" data-bs-dismiss="modal">Cerar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <?php require_once('../Html/scripts.php') ?>

    <script src='./calendar.js'></script>
    <script src='../../public/lib/calendar/lib/main.js'></script>
    <script src='../../public/lib/calendar/lib/locales/es.js'></script>

</body>

</html>