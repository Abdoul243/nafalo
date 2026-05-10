@extends('superadmin.layouts.superadmin')

@section('title', 'Contacter ' . $utilisateur->nom)
@section('page_title', 'Contacter un marchand')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="font-weight:800;font-size:1.3rem;color:#0f172a;margin:0;">
            Contacter {{ $utilisateur->nom }}
        </h2>
        <div style="color:#64748b;font-size:0.875rem;">
            <i class="fas fa-envelope me-1"></i>{{ $utilisateur->email }}
        </div>
    </div>
    <a href="{{ route('superadmin.marchands.show', $utilisateur) }}"
       class="btn btn-outline-secondary" style="border-radius:10px;">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>
</div>

<div style="max-width:680px;">
    <div class="sa-table">
        <div class="sa-table-header">
            <span><i class="fas fa-paper-plane me-2"></i>Envoyer un email</span>
        </div>
        <div style="padding:1.5rem;">

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.marchands.envoyer-email', $utilisateur) }}">
                @csrf

                {{-- Destinataire --}}
                <div class="mb-3">
                    <label style="font-weight:600;font-size:0.875rem;color:#374151;display:block;margin-bottom:6px;">
                        Destinataire
                    </label>
                    <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:0.75rem 1rem;font-size:0.9rem;color:#64748b;">
                        <i class="fas fa-user me-2" style="color:#94a3b8;"></i>
                        {{ $utilisateur->nom }} — {{ $utilisateur->email }}
                    </div>
                </div>

                {{-- Sujet --}}
                <div class="mb-3">
                    <label style="font-weight:600;font-size:0.875rem;color:#374151;display:block;margin-bottom:6px;">
                        Sujet <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" name="sujet" value="{{ old('sujet') }}"
                           class="form-control @error('sujet') is-invalid @enderror"
                           placeholder="Ex: Information importante concernant votre boutique"
                           style="border-radius:10px;border:1.5px solid #e2e8f0;padding:0.75rem 1rem;">
                    @error('sujet')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Message --}}
                <div class="mb-4">
                    <label style="font-weight:600;font-size:0.875rem;color:#374151;display:block;margin-bottom:6px;">
                        Message <span style="color:#dc2626;">*</span>
                    </label>
                    <textarea name="message" rows="8"
                              class="form-control @error('message') is-invalid @enderror"
                              placeholder="Rédigez votre message ici..."
                              style="border-radius:10px;border:1.5px solid #e2e8f0;padding:0.75rem 1rem;resize:vertical;">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Boutons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-sa" style="padding:0.75rem 1.5rem;">
                        <i class="fas fa-paper-plane me-2"></i> Envoyer l'email
                    </button>
                    <a href="{{ route('superadmin.marchands.show', $utilisateur) }}"
                       class="btn btn-outline-secondary" style="border-radius:10px;">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
