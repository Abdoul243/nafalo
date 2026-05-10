<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PixelMarketing;
use Illuminate\Http\Request;

class PixelController extends Controller
{
    public function index()
    {
        $boutiqueId = session('boutique_id');
        $pixels = PixelMarketing::where('boutique_id', $boutiqueId)->latest()->paginate(20);
        return view('admin.pixels.index', compact('pixels'));
    }

    public function create()
    {
        return view('admin.pixels.create');
    }

    public function store(Request $request)
    {
        $boutiqueId = session('boutique_id');
        $created = 0;

        // Google Tag Manager
        if ($request->filled('gtm_id')) {
            $code = "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$request->gtm_id}');</script>";

            PixelMarketing::updateOrCreate(
                ['boutique_id' => $boutiqueId, 'nom' => 'Google Tag Manager'],
                ['code_pixel' => $code, 'emplacement' => 'header', 'est_actif' => true]
            );
            $created++;
        }

        // Facebook Pixel
        if ($request->filled('facebook_pixel_id')) {
            $fbId = $request->facebook_pixel_id;
            $code = "<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{$fbId}');fbq('track','PageView');</script>";

            PixelMarketing::updateOrCreate(
                ['boutique_id' => $boutiqueId, 'nom' => 'Facebook Pixel'],
                ['code_pixel' => $code, 'emplacement' => 'header', 'est_actif' => true,
                 'meta_data' => json_encode(['pixel_id' => $fbId, 'api_token' => $request->facebook_api_token])]
            );
            $created++;
        }

        // TikTok Pixel
        if ($request->filled('tiktok_pixel_id')) {
            $tkId = $request->tiktok_pixel_id;
            $code = "<script>!function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=['page','track','identify','instances','debug','on','off','once','ready','alias','group','enableCookie','disableCookie'],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i='https://analytics.tiktok.com/i18n/pixel/events.js';ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement('script');o.type='text/javascript',o.async=!0,o.src=i+'?sdkid='+e+'&lib='+t;var a=document.getElementsByTagName('script')[0];a.parentNode.insertBefore(o,a)};ttq.load('{$tkId}');ttq.page();}(window,document,'ttq');</script>";

            PixelMarketing::updateOrCreate(
                ['boutique_id' => $boutiqueId, 'nom' => 'TikTok Pixel'],
                ['code_pixel' => $code, 'emplacement' => 'header', 'est_actif' => true]
            );
            $created++;
        }

        // Code JS personnalisé
        if ($request->filled('custom_js')) {
            PixelMarketing::updateOrCreate(
                ['boutique_id' => $boutiqueId, 'nom' => 'Code JavaScript personnalisé'],
                ['code_pixel' => $request->custom_js, 'emplacement' => 'header', 'est_actif' => true]
            );
            $created++;
        }

        if ($created === 0) {
            return redirect()->route('admin.pixels.index')
                ->with('warning', 'Aucun pixel renseigné.');
        }

        return redirect()->route('admin.pixels.index')
            ->with('success', $created . ' pixel(s) enregistré(s) avec succès.');
    }

    public function edit(PixelMarketing $pixel)
    {
        return view('admin.pixels.edit', compact('pixel'));
    }

    public function update(Request $request, PixelMarketing $pixel)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code_pixel' => 'required|string',
            'emplacement' => 'required|in:header,footer,checkout,confirmation',
            'est_actif' => 'boolean'
        ]);

        $pixel->update($validated);

        return redirect()->route('admin.pixels.index')
            ->with('success', 'Pixel mis à jour avec succès.');
    }

    public function destroy(PixelMarketing $pixel)
    {
        $pixel->delete();

        return redirect()->route('admin.pixels.index')
            ->with('success', 'Pixel supprimé avec succès.');
    }

    public function toggleActivation(PixelMarketing $pixel)
    {
        $pixel->update(['est_actif' => !$pixel->est_actif]);

        return response()->json([
            'success' => true,
            'est_actif' => $pixel->est_actif
        ]);
    }
}