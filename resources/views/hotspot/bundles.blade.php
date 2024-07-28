<x-guest-layout>
    <div class="container mt-5">
        <h1 class="text-center">Buy Hotspot Bundles</h1>
        <p class="text-center">Choose the perfect plan for your needs.</p>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Basic</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$10 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>5 GB data</li>
                            <li>Valid for 30 days</li>
                            <li>Unlimited speed</li>
                        </ul>
                        <form action="/purchase" method="POST">
                            @csrf
                            <input type="hidden" name="bundle" value="basic">
                            <button type="submit" class="btn btn-lg btn-block btn-outline-primary">Buy Now</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Standard</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$20 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>10 GB data</li>
                            <li>Valid for 30 days</li>
                            <li>Unlimited speed</li>
                        </ul>
                        <form action="/purchase" method="POST">
                            @csrf
                            <input type="hidden" name="bundle" value="standard">
                            <button type="submit" class="btn btn-lg btn-block btn-primary">Buy Now</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Premium</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$30 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>20 GB data</li>
                            <li>Valid for 30 days</li>
                            <li>Unlimited speed</li>
                        </ul>
                        <form action="/purchase" method="POST">
                            @csrf
                            <input type="hidden" name="bundle" value="premium">
                            <button type="submit" class="btn btn-lg btn-block btn-primary">Buy Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; 2024 Hotspot Bundles. All rights reserved.</span>
        </div>
    </footer>
</x-guest-layout>
