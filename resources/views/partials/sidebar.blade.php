<ul class="pc-navbar">
    <li class="pc-item pc-caption">
        <label>Umum</label>
    </li>
    <li class="pc-item">
        <a href="{{ route('dashboard') }}" class="pc-link">
            <span class="pc-micon">
                <i class="ti ti-layout"></i>
            </span>
            <span class="pc-mtext">Dashboard</span>
        </a>
    </li>
    <li class="pc-item">
        <a href="{{ route('admin.menus.index') }}" class="pc-link">
            <span class="pc-micon">
                <i class="ti ti-tools-kitchen-2"></i>
            </span>
            <span class="pc-mtext">Menu</span>
        </a>
    </li>
    <li class="pc-item">
        <a href="{{ route('admin.orders.index') }}" class="pc-link">
            <span class="pc-micon">
                <i class="ti ti-receipt"></i>
            </span>
            <span class="pc-mtext">Order</span>
        </a>
    </li>
</ul>
