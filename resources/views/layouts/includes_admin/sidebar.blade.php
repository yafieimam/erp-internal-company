  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="https://www.dwiselogirimas.com" class="brand-link">
      <img src="{{asset('app-assets/asset/images/favicon.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">PT. Dwi Selo Giri Mas</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('app-assets/asset/images/no_profile.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <?php
          if(Session::get('login_admin')){
          ?>
          <a href="#" class="d-block" style="font-size: 0.9rem; margin-top: 0.2rem;">{{ Session::get('nama_admin') }}</a>
          <?php
          }else{
          ?>
          <a href="#" class="d-block">User Name</a>
          <?php
          }
          ?>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ url('/homepage') }}" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Home
              </p>
            </a>
          </li>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 1){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Sales
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/sales/omset') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Omset
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/orders') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Orders
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/customer_visit') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customers Follow Up
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/surat_pengenalan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Surat Pengenalan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/surat_penawaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Surat Penawaran
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/uji_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Uji Kompetitor
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/permintaan_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Permintaan Sample
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/customers') }}" id="btn-sidebars-customers" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customers
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/group/customers') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customer Group
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/leads') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Leads
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/kompetitor') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kompetitor
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Complaint
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/list_message') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    List Message
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/history') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    History
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    File Manager
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-industry"></i>
              <p>
                Produksi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/produksi/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/rencana_produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rencana Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/laporan_hasil_produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Hasil Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/laporan_total_produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Total Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/laporan_rata_produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Rata-Rata Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/laporan_masalah_mesin') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Masalah Mesin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/permintaan_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Permintaan Sample
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/warehouse') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Warehouse
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/inventaris') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventaris</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/uji_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Uji Kompetitor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/list_staff') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Staff Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    File Manager
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shipping-fast"></i>
              <p>
                Logistik
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/logistik/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/logistik/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/logistik/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Warehouse
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/warehouse/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/warehouse/inventaris') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventaris</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/warehouse/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/warehouse/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-balance-scale"></i>
              <p>
                Timbangan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/timbangan/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/timbangan/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/timbangan/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-flask"></i>
              <p>
                Lab
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/lab/laporan_lab') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Hasil Lab</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/grafik') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Grafik Evaluasi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/uji_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Uji Kompetitor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/permintaan_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permintaan Sample</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Raw Material
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/raw_material/item_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Item Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/vendor_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vendor Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/purchase_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/potongan_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Potongan Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/inventaris_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventaris Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/laporan_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                HRD
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/hrd/list_resume') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    List Resume
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/hrd/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/master/company') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Company
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/unit') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Unit
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/shift') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Shift
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/bagian') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Bagian
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/karyawan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Karyawan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/parameter_kpi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Parameter KPI
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/kpi_karyawan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    KPI Karyawan
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tasks"></i>
              <p>
                Personal Assistant
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/pa/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    My Project
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/sales') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Sales
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Produksi
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/hrd') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    HRD
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/logistik') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Logistik
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/timbangan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Timbangan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/warehouse') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Warehouse
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/lab') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Lab
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/teknik') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Teknik
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/raw_material') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Raw Material
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-archive"></i>
              <p>
                Administrasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/administrasi/absensi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Absensi
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/rumus_upah') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Rumus Upah
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/payroll') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Payroll
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/pelanggaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pelanggaran
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/lembur') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Lembur
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/izin') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Izin
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/cuti') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Cuti
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/hari_besar') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Hari Besar
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/konfigurasi_upah') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Konfigurasi Upah
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Admin DSGM
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/admin/dsgm/kirim_dok') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kirim Dokumen Set
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/dsgm/pembayaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pembayaran
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/dsgm/laporan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Laporan
                  </p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="{{ url('/admin/dsgm/kirim_bg') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kirim Penerimaan BG
                  </p>
                </a>
              </li> -->
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Admin Trunojoyo
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/terima_dok') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Terima Dokumen Set
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/terima_cek') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Cek / Giro
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/jadwal_penyerahan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Jadwal Penyerahan Dok
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/jadwal_penagihan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Jadwal Penagihan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/laporan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Laporan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/dokumen_pelunasan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Dokumen Pelunasan
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Collector
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/collector/jadwal_penyerahan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Jadwal Penyerahan Dok
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/collector/jadwal_penagihan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Jadwal Penagihan
                  </p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="{{ url('/collector/kirim_cek') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kirim Cek / Giro
                  </p>
                </a>
              </li> -->
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Cashier DSGM
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- <li class="nav-item">
                <a href="{{ url('/cashier/pembayaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pembayaran
                  </p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="{{ url('/cashier/pelunasan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pelunasan
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Cashier Ondomohen
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/cashier/terima_cek') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Terima Cek / Giro
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/penagihan/cashier/terima_dok') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Terima Dokumen Set
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/cashier/terima/dokumen_pelunasan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Terima Dok Pelunasan
                  </p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="{{ url('/penagihan/pembayaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pembayaran
                  </p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="{{ url('/penagihan/pelunasan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pelunasan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/penagihan/kartu_piutang') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kartu Piutang
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/penagihan/aging_schedule') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Aging Schedule
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Teknik
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/teknik/input_rpm') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Input RPM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/masalah_mesin') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Masalah Mesin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/laporan_masalah_mesin') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Masalah Mesin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Accounting
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/accounting/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tv"></i>
              <p>
                IT
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/it/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Purchase
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/purchase/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>
                Security
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/security/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 2 || Session::get('tipe_user') == 10){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Sales
                <i class="right fas fa-angle-left"></i>
                <?php
                if($user->unreadnotifications->count()){
                ?>
                <span class="badge badge-danger right">{{ $user->unreadnotifications->count() }}</span>
                <?php
                }
                ?>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/sales/omset') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Omset
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/orders') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Orders
                    <?php
                    if($user->unreadnotifications->where('type', 'App\Notifications\NotifNewOrders')->count()){
                    ?>
                    <span class="badge badge-danger right">{{ $user->unreadnotifications->where('type', 'App\Notifications\NotifNewOrders')->count() }}</span>
                    <?php
                    }
                    ?>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/customer_visit') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customers Follow Up
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/surat_pengenalan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Surat Pengenalan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/surat_penawaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Surat Penawaran
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/uji_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Uji Kompetitor
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/permintaan_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Permintaan Sample
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/customers') }}" id="btn-sidebars-customers" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customers
                    <?php
                    if($user->unreadnotifications->where('type', 'App\Notifications\NotifNewCustomers')->count()){
                    ?>
                    <span class="badge badge-danger right">{{ $user->unreadnotifications->where('type', 'App\Notifications\NotifNewCustomers')->count() }}</span>
                    <?php
                    }
                    ?>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/group/customers') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customer Group
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/leads') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Leads
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/kompetitor') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kompetitor
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Complaint
                    <?php
                    if($user->unreadnotifications->where('type', 'App\Notifications\NotifNewComplaint')->count()){
                    ?>
                    <span class="badge badge-danger right">{{ $user->unreadnotifications->where('type', 'App\Notifications\NotifNewComplaint')->count() }}</span>
                    <?php
                    }
                    ?>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/list_message') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    List Message
                    <?php
                    if($user->unreadnotifications->where('type', 'App\Notifications\NotifNewContactUs')->count()){
                    ?>
                    <span class="badge badge-danger right">{{ $user->unreadnotifications->where('type', 'App\Notifications\NotifNewContactUs')->count() }}</span>
                    <?php
                    }
                    ?>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/history') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    History
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    File Manager
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 3){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-industry"></i>
              <p>
                Produksi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/produksi/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/rencana_produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rencana Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/laporan_hasil_produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Hasil Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/laporan_total_produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Total Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/laporan_rata_produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Rata-Rata Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/laporan_masalah_mesin') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Masalah Mesin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/permintaan_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Permintaan Sample
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/warehouse') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Warehouse
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/inventaris') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventaris</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/uji_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Uji Kompetitor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/list_staff') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Staff Produksi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/produksi/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    File Manager
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 6){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shipping-fast"></i>
              <p>
                Logistik
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/logistik/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/logistik/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/logistik/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 8){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Warehouse
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/warehouse/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/warehouse/inventaris') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventaris</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/warehouse/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/warehouse/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 7){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-balance-scale"></i>
              <p>
                Timbangan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/timbangan/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/timbangan/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/timbangan/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 9){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-flask"></i>
              <p>
                Lab
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/lab/laporan_lab') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Hasil Lab</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/grafik') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Grafik Evaluasi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/uji_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Uji Kompetitor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/permintaan_sample') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permintaan Sample</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/complaint') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Complaint</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/lab/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 18){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Raw Material
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/raw_material/item_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Item Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/vendor_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vendor Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/purchase_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/potongan_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Potongan Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/inventaris_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventaris Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/laporan_batu') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Batu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/raw_material/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 5){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                HRD
                <i class="right fas fa-angle-left"></i>
                <?php
                if($user->unreadnotifications->count()){
                ?>
                <span class="badge badge-danger right">{{ $user->unreadnotifications->count() }}</span>
                <?php
                }
                ?>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/hrd/list_resume') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    List Resume
                    <?php
                    if($user->unreadnotifications->where('type', 'App\Notifications\NotifNewResume')->count()){
                    ?>
                    <span class="badge badge-danger right">{{ $user->unreadnotifications->where('type', 'App\Notifications\NotifNewResume')->count() }}</span>
                    <?php
                    }
                    ?>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/hrd/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/master/company') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Company
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/unit') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Unit
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/shift') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Shift
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/bagian') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Bagian
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/karyawan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Karyawan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/parameter_kpi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Parameter KPI
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/kpi_karyawan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    KPI Karyawan
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 11){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-archive"></i>
              <p>
                Administrasi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/administrasi/absensi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Absensi
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/rumus_upah') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Rumus Upah
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/payroll') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Payroll
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/pelanggaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pelanggaran
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/lembur') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Lembur
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/izin') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Izin
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/cuti') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Cuti
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/hari_besar') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Hari Besar
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/administrasi/konfigurasi_upah') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Konfigurasi Upah
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 12){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Penagihan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/admin/dsgm/kirim_dok') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kirim Dokumen Set
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/dsgm/pembayaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pembayaran
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/dsgm/laporan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Laporan
                  </p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="{{ url('/admin/dsgm/kirim_bg') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kirim Penerimaan BG
                  </p>
                </a>
              </li> -->
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 13){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Penagihan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/terima_dok') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Terima Dokumen Set
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/terima_cek') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Cek / Giro
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/jadwal_penyerahan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Jadwal Penyerahan Dok
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/jadwal_penagihan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Jadwal Penagihan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/laporan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Laporan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/trunojoyo/dokumen_pelunasan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Dokumen Pelunasan
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 14){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Penagihan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/collector/jadwal_penyerahan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Jadwal Penyerahan Dok
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/collector/jadwal_penagihan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Jadwal Penagihan
                  </p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="{{ url('/collector/kirim_cek') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kirim Cek / Giro
                  </p>
                </a>
              </li> -->
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 15){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Cashier
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/cashier/pelunasan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pelunasan
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 16){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Teknik
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/teknik/input_rpm') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Input RPM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/masalah_mesin') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Masalah Mesin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/laporan_masalah_mesin') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laporan Masalah Mesin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/teknik/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 17){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Cashier
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/cashier/terima_cek') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Terima Cek / Giro
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/penagihan/cashier/terima_dok') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Terima Dokumen Set
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/cashier/terima/dokumen_pelunasan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Terima Dok Pelunasan
                  </p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="{{ url('/penagihan/pembayaran') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pembayaran
                  </p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="{{ url('/penagihan/pelunasan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Pelunasan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/penagihan/kartu_piutang') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kartu Piutang
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/penagihan/aging_schedule') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Aging Schedule
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 19){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tasks"></i>
              <p>
                Project
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/pa/project') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    My Project
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/sales') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Sales
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/produksi') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Produksi
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/hrd') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    HRD
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/logistik') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Logistik
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/timbangan') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Timbangan
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/warehouse') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Warehouse
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/lab') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Lab
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/teknik') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Teknik
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/pa/project/raw_material') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Raw Material
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 20){
              $id_user = Session::get('id_user_admin');
              $user = App\ModelUser::select('id_user as id')->where('id_user', $id_user)->first();
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Sales
                <i class="right fas fa-angle-left"></i>
                <?php
                if($user->unreadnotifications->count()){
                ?>
                <span class="badge badge-danger right">{{ $user->unreadnotifications->count() }}</span>
                <?php
                }
                ?>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/sales/customer_visit') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customers Follow Up
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/customers') }}" id="btn-sidebars-customers" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Customers
                    <?php
                    if($user->unreadnotifications->where('type', 'App\Notifications\NotifNewCustomers')->count()){
                    ?>
                    <span class="badge badge-danger right">{{ $user->unreadnotifications->where('type', 'App\Notifications\NotifNewCustomers')->count() }}</span>
                    <?php
                    }
                    ?>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/leads') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Leads
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/sales/kompetitor') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Kompetitor
                  </p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 21){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Sales
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/sales/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 22){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                HRD
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/hrd/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 23){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Accounting
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/accounting/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 24){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tv"></i>
              <p>
                IT
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/it/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 25 || Session::get('tipe_user') == 31){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Purchase
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/purchase/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 26){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-industry"></i>
              <p>
                Produksi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/produksi/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 27){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-flask"></i>
              <p>
                Lab
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/lab/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 28){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Warehouse
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/warehouse/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 29){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shield-alt"></i>
              <p>
                Security
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/security/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 30){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Teknik
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/teknik/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <?php
          if(Session::get('login_admin')){
            if(Session::get('tipe_user') == 32){
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shipping-fast"></i>
              <p>
                Logistik
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/logistik/file_manager') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>File Manager</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            }
          }
          ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sliders-h"></i>
              <p>
                Settings
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/settings/profile') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/settings/password') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Edit Password</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ url('/logoutEn') }}" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>