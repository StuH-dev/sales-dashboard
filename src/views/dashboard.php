<?php
$openOrders    = $summary['open_orders']      ?? 0;
$invoicedToday = $summary['invoiced_today']   ?? 0;
$monthToDate   = $summary['month_to_date']    ?? 0;

$labels   = array_column($rolling12, 'month_label');
$invData  = array_column($rolling12, 'invoiced');
$budData  = array_column($rolling12, 'budget');
$tarData  = array_column($rolling12, 'target');

$currentMonth = $rolling12[0] ?? end($rolling12);
$currentBudget = $currentMonth['budget'] ?? 0;
$currentTarget = $currentMonth['target'] ?? 0;

$budgetPct = $currentBudget > 0 ? min(100, ($monthToDate / $currentBudget) * 100) : 0;
$targetPct = $currentTarget > 0 ? min(100, ($monthToDate / $currentTarget) * 100) : 0;
?>

<!-- Top summary cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-between border border-slate-100">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Open Orders</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">
                $<?= number_format($openOrders, 0) ?>
            </p>
        </div>
        <div class="mt-4 text-xs">
            <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 font-medium">
                +5.2%
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-between border border-slate-100">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Invoiced Today</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">
                $<?= number_format($invoicedToday, 0) ?>
            </p>
        </div>
        <div class="mt-4 text-xs">
            <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 font-medium">
                +12.3%
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-between border border-slate-100">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Month to Date</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">
                $<?= number_format($monthToDate, 0) ?>
            </p>
        </div>
        <div class="mt-4 text-xs">
            <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 font-medium">
                +8.7%
            </span>
        </div>
    </div>
</div>

<!-- MTD performance + chart -->
<div class="grid grid-cols-1 xl:grid-cols-5 gap-4">
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="bg-brand text-white px-6 py-3 text-sm font-semibold">
            Month to Date Performance
        </div>
        <div class="p-6 space-y-6">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-slate-700">Month to Date vs Budget</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-bold text-emerald-600"><?= number_format($budgetPct, 1) ?>%</span>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                        <span>$<?= number_format($monthToDate, 0) ?></span>
                        <span>$<?= number_format($currentBudget, 0) ?></span>
                    </div>
                </div>
                <div class="relative w-full h-5 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-600 rounded-full transition-all duration-700 ease-out relative overflow-hidden" 
                         style="width:<?= number_format($budgetPct, 1) ?>%;">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-1 bg-white/40 shadow-sm"></div>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-slate-700">Month to Date vs Target</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-bold text-amber-600"><?= number_format($targetPct, 1) ?>%</span>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                        <span>$<?= number_format($monthToDate, 0) ?></span>
                        <span>$<?= number_format($currentTarget, 0) ?></span>
                    </div>
                </div>
                <div class="relative w-full h-5 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 rounded-full transition-all duration-700 ease-out relative overflow-hidden" 
                         style="width:<?= number_format($targetPct, 1) ?>%;">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-1 bg-white/40 shadow-sm"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="xl:col-span-3 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-brand text-white px-4 py-2 text-xs font-semibold">
            Rolling 12 Months Trend
        </div>
        <div class="p-4 h-64">
            <canvas id="rollingChart"
                    class="w-full h-full"
                    data-labels='<?= json_encode($labels) ?>'
                    data-invoiced='<?= json_encode($invData) ?>'
                    data-budget='<?= json_encode($budData) ?>'
                    data-target='<?= json_encode($tarData) ?>'></canvas>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm mt-4 border border-slate-100 overflow-hidden">
    <div class="bg-brand text-white px-6 py-3 text-sm font-semibold">
        Rolling 12 Months Performance
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Month</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Invoiced</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Budget</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Target</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Variance</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Target %</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
            <?php foreach ($rolling12 as $index => $row): ?>
                <?php
                $isCurrentMonth = $index === 0;
                $variance   = $row['invoiced'] - $row['target'];
                $variancePct = $row['target'] > 0 ? ($variance / $row['target']) * 100 : 0;
                $targetPct   = $row['target'] > 0 ? ($row['invoiced'] / $row['target']) * 100 : 0;
                ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900"><?= htmlspecialchars($row['month_label']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-900 font-medium">$<?= number_format($row['invoiced'], 0) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-600">$<?= number_format($row['budget'], 0) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-600">$<?= number_format($row['target'], 0) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium <?= $variance >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                        <?php if (!$isCurrentMonth): ?>
                            <?= $variance >= 0 ? '+' : '' ?><?= number_format($variancePct, 1) ?>%
                        <?php else: ?>
                            <span class="text-slate-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                        <?php if (!$isCurrentMonth): ?>
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                          bg-amber-50 text-amber-700">
                                <?= number_format($targetPct, 1) ?>%
                            </span>
                        <?php else: ?>
                            <span class="text-slate-400">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
