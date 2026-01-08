    <!-- Navbar -->

    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
        id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                    <i class="bx bx-search fs-4 lh-0"></i>
                    <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                        aria-label="Search..." />
                </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                <li class="nav-item lh-1 me-3 text-end">
                    <span class="fw-semibold d-block mt-1" style="font-size: calc(0.7rem + 0.3vw);"><?= session()->get('username'); ?></span>
                    <small class="text-muted" style="font-size: calc(0.6rem + 0.2vw);"><?= session()->get('status'); ?></small>
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <?php $persImg = session()->get('pers_img'); ?>
                            <img src="<?= $persImg ? 'https://personnel.skj.ac.th/uploads/admin/Personnal/' . $persImg : 'https://ui-avatars.com/api/?name=' . urlencode(session()->get('username')) . '&background=696cff&color=fff' ?>" 
                                onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode(session()->get('username')) ?>&background=696cff&color=fff'" 
                                alt class="w-px-40 h-px-40 rounded-circle" style="object-fit: cover;" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      
                       
                        <!-- <li>
                            <div class="dropdown-divider"></div>
                        </li> -->
                        <li>
                            <a class="dropdown-item" href="<?=base_url('/LogoutOfficerSportBase')?>">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </nav>

    <!-- / Navbar -->