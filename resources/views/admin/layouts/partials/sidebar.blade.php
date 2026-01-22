<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="index.html" class="app-brand-link">
        <span class="app-brand-logo demo">

          <img src="{{ asset('assets/theme1/img/favicon/logo.png')}}" alt="phoenix" width="27" />

        </span>
        <span class="app-brand-text demo menu-text fw-bold ms-2">ASSET</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      <!-- Dashboards -->
      <li class="menu-item @if(setActive('admin/dashboard')) active open @endif">
        <a href="{{ route('admin.dashboard.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-smile"></i>
          <div class="text-truncate" data-i18n="Tables">Dashboard</div>
        </a>
      </li>
      @if (Auth::user()->role != 'pekerja')
      <!-- Master -->
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Master</span></li>
      <!-- Forms -->
      <li class="menu-item @if(setActive('admin/user'). setActive('admin/jabatan')) active open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div class="text-truncate" data-i18n="Form Elements">Menejemen User</div>
        </a>
        <ul class="menu-sub"> 
          <li class="menu-item @if(setActive('admin/user')) active @endif">
            <a href="{{ route('admin.user.index') }}" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">User</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="forms-input-groups.html" class="menu-link">
              <div class="text-truncate" data-i18n="Input groups">Blank</div>
            </a>
          </li>
        </ul>
      </li>
    
      <li class="menu-item @if(setActive('admin/recapitulation')) active open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-table"></i>
          <div class="text-truncate" data-i18n="Form Elements">Rekapitulasi</div>
        </a>
        <ul class="menu-sub"> 
          <li class="menu-item @if(setActive('admin/recapitulation/advanced')) active @endif">
            <a href="#" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Advanced</div>
            </a>
          </li>
          
        </ul>
      </li>
      @endif
      <!-- Tables -->
      <li class="menu-item @if(setActive('admin/blank-page')) active open @endif">
        <a href="{{ route('admin.blank') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-table"></i>
          <div class="text-truncate" data-i18n="Tables">Blank</div>
        </a>
      </li>
     
    </ul>
</aside>