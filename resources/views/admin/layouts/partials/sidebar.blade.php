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
      
      <!-- Master -->
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Master</span></li>

      <!-- Fitur Manajemen User -->
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

      <!-- Fitur Data Master -->
      <li class="menu-item @if(setActive('admin/master')) active open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-core"></i>
          <div class="text-truncate" data-i18n="Form Elements">Data Master</div>
        </a>
        <ul class="menu-sub"> 
          <li class="menu-item @if(setActive('admin/master/unit')) active @endif">
            <a href="{{ route('admin.unit.index') }}" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Unit</div>
            </a>
</li>
           <li class="menu-item @if(setActive('admin/master/brand')) active @endif">
            <a href="{{ route('admin.master-brand.index') }}" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Brand</div>
            </a>
          </li>
          <li class="menu-item @if(setActive('admin/master/building')) active @endif">
            <a href="{{ route('admin.master-building.index') }}" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Building</div>
            </a>
          </li>
           <li class="menu-item @if(setActive('admin/master/room')) active @endif">
            <a href="{{ route('admin.master-room.index') }}" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Room</div>
            </a>
        </ul>
      </li>

      <!-- Fitur Asset -->
      <li class="menu-item @if(setActive('admin/asset')) active open @endif">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-treasure-chest"></i>
          <div class="text-truncate" data-i18n="Form Elements">Asset</div>
        </a>
        <ul class="menu-sub"> 
          <li class="menu-item @if(setActive('admin/asset/list')) active @endif">
            <a href="#" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Daftar Asset</div>
            </a>
          </li>
          <li class="menu-item @if(setActive('admin/asset/category')) active @endif">
            <a href="{{ route('admin.asset-category.index') }}" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Kategori Asset</div>
            </a>
          </li>
          <li class="menu-item @if(setActive('admin/asset/realization')) active @endif">
            <a href="{{ route('admin.asset-realization.index') }}" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Realisasi Asset</div>
            </a>
          </li>    
          <li class="menu-item @if(setActive('admin/asset/asset-material')) active @endif">
            <a href="{{ route('admin.asset-material.index') }}" class="menu-link">
              <div class="text-truncate" data-i18n="Basic Inputs">Asset Material</div>
            </a>
          </li>
        <li class="menu-item @if(setActive('admin/asset/asset-detail')) active @endif">
            <a href="{{ route('admin.asset-detail.index') }}" class="menu-link">
                <div class="text-truncate">Asset Detail</div>
            </a>
        </li>
    </ul>
</li>
        </ul>
      </li>
      

      <!-- Transaction -->
      <li class="menu-header small text-uppercase"><span class="menu-header-text">Transaction</span></li>

      <li class="menu-item @if(setActive('admin/blank-page')) active open @endif">
        <a href="{{ route('admin.blank') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-repeat-alt-2"></i>
          <div class="text-truncate" data-i18n="Tables">Realisasi</div>
        </a>
      </li>
    </ul>
</aside>
