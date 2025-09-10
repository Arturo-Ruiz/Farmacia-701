<footer class="bg-primary-color text-light pt-5 pb-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="rounded-circle bg-white shadow-1-strong d-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 150px; height: 150px;">
                    <img src="{{ asset('img/logo.png') }}" height="140px" alt="Farmacia 701" />
                </div>
                <p class="text-center">Encuentra todos tús medicamentos, productos de salud, cuidado personal y suplementos deportivos.</p>
                <p class="text-center">Av. 17 de diciembre C/C Calle Madrid, Local # 28, Séctor Negro Primero, Parroquia catedral, Frente a la clínica Santa Ana, Ciudad Bolívar - Venezuela.</p>
                <p class="text-center">¡Somos tus Aliados en Salud! <br> Farmacia 701, C.A</p>

                <div class="d-flex align-items-center justify-content-center gap-3">
                    <div>
                        <a href="https://api.whatsapp.com/send?phone=584141850671&text=Hola,%20Farmacia%20701." target="_blank" aria-label="Whatsapp Farmacia 701">
                            <i class="fa-brands fa-whatsapp icon-footer"></i>
                        </a>
                    </div>
                    <div>
                        <a href="https://www.instagram.com/farma701/" target="_blank" aria-label="Instagram Farmacia 701">
                            <i class="fa-brands fa-instagram icon-footer"></i>
                        </a>
                    </div>
                    <div>
                        <a href="https://www.tiktok.com/@farmacia701" target="_blank" aria-label="Tiktok Farmacia 701">
                            <i class="fa-brands fa-tiktok icon-footer"></i>

                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4 d-flex flex-column justify-content-center ">
                <h5 class="text-uppercase">Nuestra ubicación</h5>
                <div class="map-container" style="height: 250px; border-radius: 8px; overflow: hidden;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d246.8645807566945!2d-63.54489201416361!3d8.118450401078919!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8dce87a89edf52dd%3A0xad68288781bd60fb!2sFarmacia%20701%2C%20C.A.!5e0!3m2!1sen!2sus!4v1731616867323!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

        <hr class="border-light">

        <div class="text-center pt-3">
            <p class="mb-0">&copy; <span id="current-year"></span>
                <a href="{{ route('admin.dashboard') }}" class="text-light text-decoration-none">Farmacia 701</a>.
                Todos los derechos reservados.
            </p>
        </div>
    </div>
</footer>