    <!-- Navbar -->

    <!-- Navbar -->
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4 text-primary" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Breadcrumbs / Title -->
            <div class="navbar-nav align-items-center">
                <div class="nav-item mb-0">
                    <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">
                        <span class="text-primary">SKJ SportBase</span> 
                        <span class="text-muted fw-light mx-2 d-none d-md-inline">|</span> 
                        <span class="opacity-75 d-none d-md-inline"><?= $title; ?></span>
                    </h5>
                </div>
            </div>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <?php if(isset($_SESSION['username'])): ?>
                <li class="nav-item me-3 text-end">
                    <div class="fw-bold text-dark lh-1" style="font-size: calc(0.7rem + 0.3vw);"><?= $_SESSION['username'] ?></div>
                    <?php 
                        $statusClass = 'bg-label-primary';
                        if($_SESSION['status'] == 'admin') $statusClass = 'bg-label-danger';
                        if($_SESSION['status'] == 'coach') $statusClass = 'bg-label-info';
                    ?>
                    <small class="badge <?= $statusClass ?> rounded-pill mt-1" style="font-size: calc(0.55rem + 0.1vw);">
                        <i class='bx bxs-circle me-1' style="font-size: 0.5rem;"></i> <?= $_SESSION['status'] ?>
                    </small>
                </li>
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online shadow-sm">
                            <?php $persImg = $_SESSION['pers_img'] ?? null; ?>
                            <img src="<?= $persImg ? 'https://personnel.skj.ac.th/uploads/admin/Personnal/' . $persImg : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=696cff&color=fff' ?>" 
                                onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username']) ?>&background=696cff&color=fff'" 
                                alt="User" class="w-px-40 h-px-40 rounded-circle" style="object-fit: cover;">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2" style="border-radius: 12px;">
                        <li>
                            <div class="dropdown-header d-flex align-items-center p-3">
                                <div class="avatar avatar-online me-3">
                                    <img src="<?= $persImg ? 'https://personnel.skj.ac.th/uploads/admin/Personnal/' . $persImg : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['username']) . '&background=696cff&color=fff' ?>" 
                                        onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username']) ?>&background=696cff&color=fff'" 
                                        class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-bold d-block"><?= $_SESSION['username'] ?></span>
                                    <small class="text-muted"><?= $_SESSION['status'] ?></small>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li>
                            <a class="dropdown-item py-2" href="<?= base_url('Admin/Home') ?>">
                                <i class="bx bx-cog me-2"></i> จัดการระบบ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="<?= base_url('/LogoutOfficerSportBase') ?>">
                                <i class="bx bx-power-off me-2"></i> ออกจากระบบ
                            </a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a href="<?= base_url('LoginOfficerSportBase') ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow">
                        <i class='bx bx-log-in me-1'></i> เข้าสู่ระบบ
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- / Navbar -->