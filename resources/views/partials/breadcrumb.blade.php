@if (isset($breadcrumb_link))
    <li>
        <a href='{{isset($router) ? route($router) :'javascript:void(0)'}}'>{{ $breadcrumb_link }}</a>
    </li>
@endif