<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 32px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        h1 { font-size: 18px; margin: 0 0 2px 0; color: #111827; }
        h2 { font-size: 13px; margin: 22px 0 8px 0; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
        .muted { color: #6b7280; }
        .header-table { width: 100%; margin-bottom: 4px; }
        .header-table td { vertical-align: top; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .summary-table td {
            width: 25%; padding: 10px; border: 1px solid #e5e7eb; text-align: left;
        }
        .summary-table .label { font-size: 9px; color: #6b7280; display: block; margin-bottom: 3px; }
        .summary-table .value { font-size: 13px; font-weight: bold; color: #111827; }
        .value.green { color: #059669; }
        .value.red { color: #dc2626; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th {
            background: #f9fafb; text-align: left; padding: 6px 8px; font-size: 9px;
            text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #e5e7eb;
        }
        table.data td { padding: 6px 8px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block; padding: 2px 7px; border-radius: 8px; font-size: 9px; font-weight: bold;
        }
        .badge-expense { background: #fee2e2; color: #b91c1c; }
        .badge-income { background: #d1fae5; color: #047857; }
        .progress-bg { background: #f3f4f6; height: 6px; border-radius: 4px; width: 100%; }
        .progress-fill { height: 6px; border-radius: 4px; background: #6366f1; }
        .footer { position: fixed; bottom: -10px; left: 0; right: 0; font-size: 8px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <h1>Nzolo Cash</h1>
                <p class="muted">Rapport financier — {{ ucfirst($period->translatedFormat('F Y')) }}</p>
            </td>
            <td style="text-align: right;">
                <p class="muted">{{ $user->name }}</p>
                <p class="muted">Généré le {{ $generatedAt->translatedFormat('d M Y à H:i') }}</p>
            </td>
        </tr>
    </table>

    {{-- Résumé du mois --}}
    <h2>Résumé du mois</h2>
    <table class="summary-table">
        <tr>
            <td>
                <span class="label">SOLDE TOTAL</span>
                <span class="value">{{ number_format($totalBalance, 0, ',', ' ') }} FCFA</span>
            </td>
            <td>
                <span class="label">REVENUS</span>
                <span class="value green">{{ number_format($income, 0, ',', ' ') }} FCFA</span>
            </td>
            <td>
                <span class="label">DÉPENSES</span>
                <span class="value red">{{ number_format($expense, 0, ',', ' ') }} FCFA</span>
            </td>
            <td>
                <span class="label">SOLDE NET DU MOIS</span>
                <span class="value {{ $net >= 0 ? 'green' : 'red' }}">{{ number_format($net, 0, ',', ' ') }} FCFA</span>
            </td>
        </tr>
    </table>

    {{-- Comptes --}}
    <h2>Comptes ({{ $accounts->count() }})</h2>
    @if ($accounts->isEmpty())
        <p class="muted">Aucun compte enregistré.</p>
    @else
        <table class="data">
            <thead>
                <tr><th>Nom</th><th>Type</th><th class="text-right">Solde</th></tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <td>{{ $account->name }}</td>
                        <td class="muted">{{ ucfirst($account->type) }}</td>
                        <td class="text-right">{{ number_format($account->balance, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Répartition des dépenses --}}
    <h2>Répartition des dépenses par catégorie</h2>
    @if ($expensesByCategory->isEmpty())
        <p class="muted">Aucune dépense ce mois-ci.</p>
    @else
        <table class="data">
            <thead>
                <tr><th>Catégorie</th><th class="text-right">Montant</th><th class="text-right">Part</th></tr>
            </thead>
            <tbody>
                @foreach ($expensesByCategory as $category => $amount)
                    <tr>
                        <td>{{ $category }}</td>
                        <td class="text-right">{{ number_format($amount, 0, ',', ' ') }} FCFA</td>
                        <td class="text-right">{{ $expense > 0 ? round(($amount / $expense) * 100) : 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Budgets --}}
    <h2>Budgets du mois</h2>
    @if ($budgets->isEmpty())
        <p class="muted">Aucun budget défini pour ce mois.</p>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Catégorie</th><th class="text-right">Limite</th>
                    <th class="text-right">Dépensé</th><th class="text-right">Restant</th><th>Progression</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($budgets as $budget)
                    <tr>
                        <td>{{ $budget['category'] }}</td>
                        <td class="text-right">{{ number_format($budget['limit'], 0, ',', ' ') }} FCFA</td>
                        <td class="text-right">{{ number_format($budget['spent'], 0, ',', ' ') }} FCFA</td>
                        <td class="text-right {{ $budget['remaining'] < 0 ? 'red' : '' }}">
                            {{ number_format($budget['remaining'], 0, ',', ' ') }} FCFA
                        </td>
                        <td style="width: 90px;">
                            <div class="progress-bg">
                                <div class="progress-fill" style="width: {{ $budget['percent'] }}%; {{ $budget['percent'] >= 100 ? 'background:#dc2626;' : ($budget['percent'] >= 80 ? 'background:#f59e0b;' : '') }}"></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Objectifs d'épargne --}}
    <h2>Objectifs d'épargne</h2>
    @if ($savingGoals->isEmpty())
        <p class="muted">Aucun objectif d'épargne.</p>
    @else
        <table class="data">
            <thead>
                <tr><th>Objectif</th><th class="text-right">Épargné</th><th class="text-right">Cible</th><th>Progression</th></tr>
            </thead>
            <tbody>
                @foreach ($savingGoals as $goal)
                    @php $percent = $goal->target_amount > 0 ? min(100, round(($goal->current_amount / $goal->target_amount) * 100)) : 0; @endphp
                    <tr>
                        <td>{{ $goal->name }}</td>
                        <td class="text-right">{{ number_format($goal->current_amount, 0, ',', ' ') }} FCFA</td>
                        <td class="text-right">{{ number_format($goal->target_amount, 0, ',', ' ') }} FCFA</td>
                        <td style="width: 90px;">
                            <div class="progress-bg">
                                <div class="progress-fill" style="width: {{ $percent }}%; background: {{ $goal->color }};"></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Transactions --}}
    <h2>Transactions du mois ({{ $transactionsCount }}{{ $transactionsCount > 40 ? ' — 40 affichées' : '' }})</h2>
    @if ($transactions->isEmpty())
        <p class="muted">Aucune transaction ce mois-ci.</p>
    @else
        <table class="data">
            <thead>
                <tr><th>Date</th><th>Description</th><th>Catégorie</th><th>Compte</th><th class="text-right">Montant</th></tr>
            </thead>
            <tbody>
                @foreach ($transactions as $t)
                    <tr>
                        <td>{{ $t->date->format('d/m/Y') }}</td>
                        <td>{{ $t->description ?: '—' }}</td>
                        <td>{{ $t->category->name ?? '—' }}</td>
                        <td class="muted">{{ $t->account->name ?? '—' }}</td>
                        <td class="text-right">
                            <span class="badge {{ $t->type === 'expense' ? 'badge-expense' : 'badge-income' }}">
                                {{ $t->type === 'expense' ? '-' : '+' }}{{ number_format($t->amount, 0, ',', ' ') }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">{{ $appName }} — Rapport généré automatiquement, à titre informatif.</div>

</body>
</html>