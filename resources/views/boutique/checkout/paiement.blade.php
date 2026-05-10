@extends('layouts.boutique')

@section('title', 'Paiement')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3">Paiement test (Kkiapay)</h2>
                <p class="text-muted mb-4">
                    Montant à payer: <strong>{{ number_format($total, 2) }} {{ $boutique->configuration->devise ?? 'XOF' }}</strong>
                </p>

                <form id="kkiapay-fallback-form" method="POST" action="{{ route('boutique.checkout.paiement') }}" class="d-none">
                    @csrf
                    <input type="hidden" name="payment_method_id" value="kkiapay-test">
                </form>

                <script src="https://cdn.kkiapay.me/k.js"></script>
                <kkiapay-widget
                    amount="{{ (int) round($total) }}"
                    key="a185aa90fd5311f0be04dd6d8b783d77"
                    position="center"
                    sandbox="true"
                    data="boutique-{{ $boutique->id }}"
                    callback="{{ route('boutique.checkout.paiement') }}">
                </kkiapay-widget>

                <button type="button" class="btn btn-primary mt-4" id="payer-test-btn">
                    Confirmer le paiement test
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('payer-test-btn').addEventListener('click', function () {
    document.getElementById('kkiapay-fallback-form').submit();
});
</script>
@endpush
