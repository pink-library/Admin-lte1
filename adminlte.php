<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db5d";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$q        = trim($_GET['q'] ?? '');
$predikat = $_GET['predikat'] ?? '';
$sort     = (($_GET['sort'] ?? '') === 'nilai') ? 'nilai' : '';
$dir      = (($_GET['dir'] ?? 'desc') === 'asc') ? 'asc' : 'desc';
$per_page = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));

$where  = [];
$types  = '';
$params = [];
if ($q !== '') {
  $where[]  = "nama_lengkap LIKE ?";
  $types   .= 's';
  $params[] = '%' . $q . '%';
}
if ($predikat !== '') {
  $where[]  = "predikat = ?";
  $types   .= 's';
  $params[] = $predikat;
}
$where_sql = $where ? " WHERE " . implode(" AND ", $where) : "";

$stmt = $conn->prepare("SELECT COUNT(*) FROM nilaimhsw" . $where_sql);
if ($params) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();

$total_pages = max(1, (int) ceil($total / $per_page));
$page   = min($page, $total_pages);
$offset = ($page - 1) * $per_page;
$dari   = ($total > 0) ? $offset + 1 : 0;
$sampai = min($offset + $per_page, $total);

$order_sql = ($sort === 'nilai')
  ? " ORDER BY nilai_akhir " . strtoupper($dir) . ", nim ASC"
  : " ORDER BY nim ASC";
$dir_berikut = ($sort === 'nilai' && $dir === 'asc') ? 'desc' : 'asc';
if ($sort === 'nilai') {
  $ikon_sort = ($dir === 'asc') ? 'bi-caret-up-fill' : 'bi-caret-down-fill';
} else {
  $ikon_sort = 'bi-arrow-down-up';
}

function url_hal($ubah = []) {
  return htmlspecialchars('?' . http_build_query(array_merge($_GET, $ubah)));
}

$daftar_predikat = $conn->query("SELECT DISTINCT predikat FROM nilaimhsw ORDER BY predikat");

$sql = "SELECT nim, nama_lengkap, nilai_akhir, nilai_huruf, predikat, reg_date, tgl_diubah FROM nilaimhsw"
     . $where_sql . $order_sql . " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$param_akhir = array_merge($params, [$per_page, $offset]);
$stmt->bind_param($types . 'ii', ...$param_akhir);
$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Users</title>

    <!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->
    <script>
      (() => {
        'use strict';
        const root = document.documentElement;

        // Applications with their own theming opt out of AdminLTE's color mode
        // entirely, here as well as in the bundle.
        if (root.getAttribute('data-lte-color-mode') === 'off') {
          return;
        }

        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try {
          stored = localStorage.getItem(STORAGE_KEY);
        } catch {
          // localStorage may be unavailable (private mode, sandboxed iframe).
        }
        // Mirror the precedence in color-mode.ts: the visitor's stored choice
        // wins, then a theme this page declared itself, then the OS preference.
        const authored = root.getAttribute('data-bs-theme');
        let resolved = 'light';
        if (stored === 'dark' || stored === 'light') {
          resolved = stored;
        } else if (authored === 'dark' || authored === 'light') {
          resolved = authored;
        } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
          resolved = 'dark';
        }
        root.setAttribute('data-bs-theme', resolved);
        root.style.colorScheme = resolved;
        // Flag values computed here, so the bundle does not mistake them for a
        // theme the page declared and stop following the OS preference.
        if (resolved !== authored) {
          root.setAttribute('data-lte-theme-resolved', '');
        }
      })();
    </script>
    <!--end::Theme Init-->

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE 4 | Users" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a free Bootstrap 5 admin dashboard template with almost 50 example pages, built with vanilla JS and designed with accessibility in mind."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="./css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="./css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h1 class="mb-0 fs-3">Rekap Nilai Mahasiswa</h1>
              </div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nilai</li>
                  </ol>
                </nav>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-12">
                <!--begin::Card-->
                <div class="card mb-4">
                  <!--begin::Card Header-->
                  <div class="card-header">
                    <div class="row g-2 align-items-center">
                      <div class="col-12 col-md-4">
                        <h3 class="card-title">Direktori Nilai</h3>
                      </div>
                      <div class="col-12 col-md-8">
                        <form method="get" class="d-flex flex-wrap justify-content-md-end gap-2">
                          <div class="input-group input-group-sm w-auto">
                            <button type="submit" class="input-group-text" style="cursor: pointer" aria-label="Cari">
                              <i class="bi bi-search" aria-hidden="true"></i>
                            </button>
                            <input
                              type="search"
                              id="user-search"
                              name="q"
                              value="<?= htmlspecialchars($q) ?>"
                              class="form-control"
                              placeholder="Cari nama"
                              aria-label="Cari nama"
                              style="width: 180px"
                            />
                          </div>
                          <select
                            id="user-role-filter"
                            name="predikat"
                            class="form-select form-select-sm w-auto"
                            aria-label="Filter berdasarkan predikat"
                            onchange="this.form.submit()"
                          >
                            <option value="">Semua predikat</option>
                            <?php while ($p = $daftar_predikat->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($p['predikat']) ?>" <?= $predikat === $p['predikat'] ? 'selected' : '' ?>>
                              <?= htmlspecialchars($p['predikat']) ?>
                            </option>
                            <?php endwhile; ?>
                          </select>
                          <?php if ($sort === 'nilai'): ?>
                          <input type="hidden" name="sort" value="nilai" />
                          <input type="hidden" name="dir" value="<?= $dir ?>" />
                          <?php endif; ?>
                          <a href="../../form_nilaimhsw.html" class="btn btn-sm btn-primary">
                            <i class="bi bi-person-plus-fill me-1" aria-hidden="true"> </i>
                            New user
                          </a>
                        </form>
                      </div>
                    </div>
                  </div>
                  <!--end::Card Header-->
                  <!--begin::Card Body-->
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-hover align-middle m-0">
                        <thead>
                          <tr>
                            <th>NIM</th>
                            <th></th>
                            <th>Nama Lengkap</th>
                            <th>
                              <a href="<?= url_hal(['sort' => 'nilai', 'dir' => $dir_berikut, 'page' => 1]) ?>"
                                 class="text-reset text-decoration-none">
                                Nilai Akhir <i class="bi <?= $ikon_sort ?>" aria-hidden="true"></i>
                              </a>
                            </th>
                            <th>Nilai Huruf</th>
                            <th>Predikat</th>
                            <th>Tgl Dibuat</th>
                            <th>Tgl Diubah</th>
                            <th class="text-end">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                          ?>
                          <tr>
                            <td><?= $row["nim"] ?></td>
                            <td>
                              <img
                                src="./assets/img/user2-160x160.jpg"
                                alt=""
                                class="img-size-32 rounded-circle"
                              />
                            </td>
                            <td>
                              <div class="d-flex align-items-center">
                                <span class="fw-medium"><?= $row["nama_lengkap"] ?></span>
                              </div>
                            </td>
                            <td><?= $row["nilai_akhir"] ?></td>
                            <td><?= $row["nilai_huruf"] ?></td>
                            <td><?= $row["predikat"] ?></td>
                            <td><?= $row["reg_date"] ?></td>
                            <td><?= $row["tgl_diubah"] ?></td>
                            <td class="text-end">
                              <div class="btn-group btn-group-sm">
                                <a
                                  href="../../form_editnilaimhsw.php?nim=<?= $row["nim"] ?>"
                                  class="btn btn-outline-secondary"
                                  aria-label="Edit <?= $row["nama_lengkap"] ?>"
                                >
                                  <i class="bi bi-pencil" aria-hidden="true"> </i>
                                </a>
                                <a
                                  href="../../hapusnilaimhsw.php?nim=<?= $row["nim"] ?>"
                                  class="btn btn-outline-danger"
                                  onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                  aria-label="Delete <?= $row["nama_lengkap"] ?>"
                                >
                                  <i class="bi bi-trash" aria-hidden="true"> </i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          <?php
                            }
                          } else {
                            echo "<tr><td colspan='9'>0 results - Data not found</td></tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.table-responsive -->
                  </div>
                  <!--end::Card Body-->
                  <!--begin::Card Footer-->
                  <div class="card-footer clearfix">
                    <div class="float-start pt-1 fs-7 text-body-secondary">
                      Menampilkan <?= $dari ?> sampai <?= $sampai ?> dari <?= $total ?> data
                    </div>
                    <ul class="pagination pagination-sm m-0 float-end">
                      <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= url_hal(['page' => $page - 1]) ?>" aria-label="Previous"> &laquo; </a>
                      </li>
                      <?php
                      $awal  = max(1, $page - 3);
                      $akhir = min($total_pages, $awal + 6);
                      $awal  = max(1, $akhir - 6);
                      for ($i = $awal; $i <= $akhir; $i++):
                      ?>
                      <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= url_hal(['page' => $i]) ?>"><?= $i ?></a>
                      </li>
                      <?php endfor; ?>
                      <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= url_hal(['page' => $page + 1]) ?>" aria-label="Next"> &raquo; </a>
                      </li>
                    </ul>
                  </div>
                  <!--end::Card Footer-->
                </div>
                <!--end::Card-->
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->

            <!--begin::Add User Modal-->
            <div
              class="modal fade"
              id="modal-add-user"
              tabindex="-1"
              aria-labelledby="modal-add-user-label"
              aria-hidden="true"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <form>
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal-add-user-label">Add new user</h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                      ></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="new-user-name" class="form-label"> Full name </label>
                        <input
                          type="text"
                          class="form-control"
                          id="new-user-name"
                          placeholder="e.g. Jane Doe"
                          required
                        />
                      </div>
                      <div class="mb-3">
                        <label for="new-user-email" class="form-label"> Email address </label>
                        <input
                          type="email"
                          class="form-control"
                          id="new-user-email"
                          placeholder="name@example.com"
                          required
                        />
                        <div class="form-text">The invitation will be sent to this address.</div>
                      </div>
                      <div class="mb-3">
                        <label for="new-user-role" class="form-label"> Role </label>
                        <select id="new-user-role" class="form-select">
                          <option selected>Subscriber</option>
                          <option>Author</option>
                          <option>Editor</option>
                          <option>Administrator</option>
                        </select>
                      </div>
                      <div class="form-check">
                        <input
                          class="form-check-input"
                          type="checkbox"
                          id="new-user-welcome"
                          checked
                        />
                        <label class="form-check-label" for="new-user-welcome">
                          Send a welcome email with login details
                        </label>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                      </button>
                      <button type="submit" class="btn btn-primary">Create user</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!--end::Add User Modal-->

            <!--begin::Delete User Modal-->
            <div
              class="modal fade"
              id="modal-delete-user"
              tabindex="-1"
              aria-labelledby="modal-delete-user-label"
              aria-hidden="true"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modal-delete-user-label">Delete user</h5>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <p class="mb-0">
                      Are you sure you want to delete this user? All content owned by the account
                      will be reassigned to the site administrator. This action cannot be undone.
                    </p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      Cancel
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                      Delete user
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!--end::Delete User Modal-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Mahasiswa Teknik Informatika</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Politeknik Negeri Pontianak &copy; 2026
        </strong>
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="./js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!--begin::Color Mode Toggle-->
    <!-- The light/dark/auto switcher ships in adminlte.js as the ColorMode
     module (since 4.1) — no page script needed. Only the no-flash snippet
     in <head> stays inline, because it must run before first paint. -->
    <!--end::Color Mode Toggle-->

    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
<?php
$conn->close();
?>