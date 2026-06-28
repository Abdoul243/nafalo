<style>
.wz { max-width:820px; margin:0 auto; padding:1.5rem 1.25rem 5rem; }
.wz-hd { display:flex; align-items:center; gap:14px; margin-bottom:1.5rem; }
.wz-hd .ic { width:54px;height:54px;border-radius:14px;background:var(--wz-accent,#f59e0b);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.35rem;flex-shrink:0; }
.wz-hd h1 { font-size:1.25rem;font-weight:800;color:#111827;margin:0; }
.wz-hd p { font-size:0.9rem;color:#9ca3af;margin:2px 0 0; }

.wz-bar { display:flex; gap:8px; margin-bottom:2rem; }
.wz-bar span { flex:1; height:7px; border-radius:7px; background:#e9eaeb; transition:background .25s; }
.wz-bar span.done { background:#111827; }

.wz-h2 { font-family:Georgia,'Times New Roman',serif; font-size:2rem; font-weight:700; color:#111827; margin:0 0 2rem; }

.wz-field { margin-bottom:1.5rem; }
.wz-field > label { display:block; font-size:0.98rem; font-weight:600; color:#1f2937; margin-bottom:0.6rem; }
.wz-field .req { color:#ef4444; }
.wz-field input, .wz-field select, .wz-field textarea {
    width:100%; border:1px solid #e5e7eb; border-radius:14px; padding:0.95rem 1.1rem; font-size:0.95rem; font-family:inherit; outline:none; color:#111827; background:#fff;
}
.wz-field input::placeholder, .wz-field textarea::placeholder { color:#9ca3af; }
.wz-field input:focus, .wz-field select:focus, .wz-field textarea:focus { border-color:var(--wz-accent,#f59e0b); box-shadow:0 0 0 3px color-mix(in srgb, var(--wz-accent,#f59e0b) 14%, transparent); }
.wz-field select { -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239ca3af' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 1.1rem center; }
.wz-2col { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
@media(max-width:560px){ .wz-2col{ grid-template-columns:1fr; } }
.wz-err { color:#dc2626; font-size:0.8rem; margin-top:5px; display:none; }
.wz-suffix { position:relative; }
.wz-suffix input { padding-right:3.2rem; }
.wz-suffix .u { position:absolute; right:1.1rem; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:0.85rem; font-weight:600; pointer-events:none; }

.wz-upload { border:2px dashed #e5e7eb; border-radius:14px; padding:2rem; text-align:center; cursor:pointer; color:#9ca3af; }
.wz-upload:hover { border-color:var(--wz-accent,#f59e0b); }
.wz-upload i { font-size:2rem; }
.wz-info { background:#f8f9fb; border:1px solid #e9eaeb; border-radius:14px; padding:1.1rem 1.25rem; color:#374151; font-size:0.9rem; }

.wz-opt { flex:1; min-width:180px; border:1.5px solid #e5e7eb; border-radius:14px; padding:0.9rem 1.1rem; cursor:pointer; display:flex; align-items:center; gap:10px; }
.wz-opt.sel { border-color:var(--wz-accent,#f59e0b); }
.wz-preset { border:1.5px solid #e5e7eb; background:#fff; border-radius:11px; padding:0.55rem 1.1rem; font-weight:700; font-size:0.85rem; color:#374151; cursor:pointer; }
.wz-preset.sel { border-color:var(--wz-accent,#f59e0b); background:var(--wz-accent,#f59e0b); color:#fff; }

.wz-recap-row { display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid #f3f4f6; font-size:0.92rem; }
.wz-recap-row .k { color:#6b7280; } .wz-recap-row .v { font-weight:700; color:#111827; }

.wz-bi { display:flex; align-items:center; gap:11px; padding:0.7rem 0.9rem; border:1px solid #eee; border-radius:12px; margin-bottom:0.5rem; cursor:pointer; }
.wz-bi:hover { background:#f8fafc; } .wz-bi input { width:18px; height:18px; accent-color:var(--wz-accent,#f59e0b); }
.wz-bi .n { flex:1; font-weight:600; color:#111827; font-size:0.9rem; } .wz-bi .p { font-size:0.8rem; color:#9ca3af; }

.wz-nav { display:flex; justify-content:flex-end; gap:0.75rem; margin-top:2rem; }
.wz-btn { border:none; border-radius:14px; padding:0.9rem 2.2rem; font-weight:700; font-size:0.95rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; }
.wz-btn.next { background:var(--wz-accent,#f59e0b); color:#fff; }
.wz-btn.next:hover { filter:brightness(0.92); color:#fff; }
.wz-btn.cancel, .wz-btn.back { background:#fff; color:#374151; border:1px solid #e5e7eb; }
.wz-btn.cancel:hover, .wz-btn.back:hover { background:#f9fafb; }
.wz-btn.back { margin-right:auto; color:#6b7280; }
</style>
