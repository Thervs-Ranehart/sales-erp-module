<div class="sidebar">

    <h4>CRM</h4>


    <a href="{{ route('crm.index') }}"
       class="{{ request()->routeIs('crm.index') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        CRM Dashboard
    </a>


    <div class="menu-title">
        Customer Management
    </div>


    <a href="{{ route('crm.directory') }}"
       class="{{ request()->routeIs('crm.directory') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        Customer Directory
    </a>



    <a href="{{ route('crm.profiles') }}"
       class="{{ request()->routeIs('crm.profiles') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i>
        Customer Profiles
    </a>



    <a href="{{ route('crm.purchase') }}"
       class="{{ request()->routeIs('crm.purchase') ? 'active' : '' }}">
        <i class="bi bi-cart-check"></i>
        Purchase History
    </a>



    <a href="{{ route('crm.logs') }}"
       class="{{ request()->routeIs('crm.logs') ? 'active' : '' }}">
        <i class="bi bi-chat-left-text"></i>
        Communication Logs
    </a>



    <a href="{{ route('crm.followups') }}"
       class="{{ request()->routeIs('crm.followups') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i>
        Follow-Ups
    </a>



    <a href="{{ route('crm.loyalty') }}"
       class="{{ request()->routeIs('crm.loyalty') ? 'active' : '' }}">
        <i class="bi bi-award"></i>
        Loyalty Program
    </a>



    <a href="{{ route('crm.segmentation') }}"
       class="{{ request()->routeIs('crm.segmentation') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i>
        Customer Segmentation
    </a>

    <a href="{{ route('crm.campaigns') }}"
       class="{{ request()->routeIs('crm.campaigns*') ? 'active' : '' }}">
        <i class="bi bi-megaphone"></i>
        Marketing Campaigns
    </a>


</div>
