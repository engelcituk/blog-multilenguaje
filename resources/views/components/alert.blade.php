@if(session('message'))
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="alert alert-{{ session('message')["type"] }}">
                <h4 class="alert-heading">{{ session('message')["title"] }}</h4>
                <p>{{ session('message')["message"] }}</p>
            </div>
        </div>
    </div>
@endif
