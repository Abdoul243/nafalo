<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationMarchand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /** Liste complète des notifications */
    public function index()
    {
        $notifications = NotificationMarchand::where('utilisateur_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /** JSON : les 10 dernières + nombre non lues (pour le dropdown) */
    public function recentes()
    {
        $userId = Auth::id();

        $notifications = NotificationMarchand::where('utilisateur_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($n) => [
                'id'        => $n->id,
                'type'      => $n->type,
                'titre'     => $n->titre,
                'message'   => $n->message,
                'lien'      => $n->lien,
                'lue'       => $n->estLue(),
                'icone'     => $n->icone(),
                'couleur'   => $n->couleur(),
                'couleurBg' => $n->couleurBg(),
                'temps'     => $n->created_at->diffForHumans(),
            ]);

        $nonLues = NotificationMarchand::where('utilisateur_id', $userId)
            ->whereNull('lu_le')
            ->count();

        return response()->json(compact('notifications', 'nonLues'));
    }

    /** Marquer une notification comme lue */
    public function marquerLue(NotificationMarchand $notification)
    {
        abort_if($notification->utilisateur_id !== Auth::id(), 403);
        $notification->marquerLue();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }
        if ($notification->lien) {
            return redirect($notification->lien);
        }
        return back();
    }

    /** Marquer toutes comme lues */
    public function marquerToutesLues()
    {
        NotificationMarchand::where('utilisateur_id', Auth::id())
            ->whereNull('lu_le')
            ->update(['lu_le' => now()]);

        return response()->json(['ok' => true]);
    }

    /** Supprimer une notification */
    public function destroy(NotificationMarchand $notification)
    {
        abort_if($notification->utilisateur_id !== Auth::id(), 403);
        $notification->delete();

        return response()->json(['ok' => true]);
    }
}
