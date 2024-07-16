<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <h3>Administration</h3>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row p-3">
                            <div class="col-md-4">
                                <a href="{{ route('users.index') }}" class="">
                                    <i class="fa-solid fa-users-line pe-2"></i> Administrators
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('roles.index') }}" class="">
                                    <i class="fa-solid fa-users-rays pe-2"></i> Roles
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="javascript:;" class="">
                                    <i class="fa-solid fa-map-location-dot pe-2"></i> Locations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
