<?php
$openOrders    = $summary['open_orders']      ?? 0;
$invoicedToday = $summary['invoiced_today']   ?? 0;
$monthToDate   = $summary['month_to_date']    ?? 0;

$labels   = array_column($rolling12, 'month_label');
$invData  = array_column($rolling12, 'invoiced');
$budData  = array_column($rolling12, 'budget');
$tarData  = array_column($rolling12, 'target');
?>

<!-- Top summary cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Open Orders</p>
            <p class="mt-1 text-2xl font-semibold">
                $<?= number_format($openOrders, 0) ?>
            </p>
        </div>
        <div class="mt-3 text-xs">
            <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5">
                +5.2%
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Invoiced Today</p>
            <p class="mt-1 text-2xl font-semibold">
                $<?= number_format($invoicedToday, 0) ?>
            </p>
        </div>
        <div class="mt-3 text-xs">
            <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5">
                +12.3%
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Month to Date</p>
            <p class="mt-1 text-2xl font-semibold">
                $<?= number_format($monthToDate, 0) ?>
            </p>
        </div>
        <div class="mt-3 text-xs">
            <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-2 py-0.5">
                +8.7%
            </span>
        </div>
    </div>
</div>

<!-- MTD performance + chart -->
<div class="grid grid-cols-1 xl:grid-cols-5 gap-4">
    <div class="xl:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-brand text-white px-4 py-2 text-xs font-semibold">
            Month to Date Performance
        </div>
        <div class="p-4 space-y-4">
            <div>
                <p class="text-xs text-slate-500 mb-1">Month to Date vs Budget</p>
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width:83.3%;"></div>
                </div>
                <p class="mt-1 text-xs text-slate-500">83.3%</p>
            </div>

            <div>
                <p class="text-xs text-slate-500 mb-1">Month to Date vs Target</p>
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500" style="width:71.4%;"></div>
                </div>
                <p class="mt-1 text-xs text-slate-500">71.4%</p>
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
<div class="bg-white rounded-xl shadow-sm mt-4">
    <div class="px-4 py-3 border-b border-slate-200">
        <h2 class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
            Rolling 12 Months Performance
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs">
            <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="px-4 py-2 text-left font-medium">Month</th>
                <th class="px-4 py-2 text-right font-medium">Invoiced</th>
                <th class="px-4 py-2 text-right font-medium">Budget</th>
                <th class="px-4 py-2 text-right font-medium">Target</th>
                <th class="px-4 py-2 text-right font-medium">Variance</th>
                <th class="px-4 py-2 text-right font-medium">Target %</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <?php foreach ($rolling12 as $row): ?>
                <?php
                $variance   = $row['invoiced'] - $row['target'];
                $variancePct = $row['target'] > 0 ? ($variance / $row['target']) * 100 : 0;
                $targetPct   = $row['target'] > 0 ? ($row['invoiced'] / $row['target']) * 100 : 0;
                ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-2"><?= htmlspecialchars($row['month_label']) ?></td>
                    <td class="px-4 py-2 text-right">$<?= number_format($row['invoiced'], 0) ?></td>
                    <td class="px-4 py-2 text-right">$<?= number_format($row['budget'], 0) ?></td>
                    <td class="px-4 py-2 text-right">$<?= number_format($row['target'], 0) ?></td>
                    <td class="px-4 py-2 text-right <?= $variance >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                        <?= $variance >= 0 ? '+' : '' ?><?= number_format($variancePct, 1) ?>%
                    </td>
                    <td class="px-4 py-2 text-right">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5
                                      bg-amber-50 text-amber-700">
                            <?= number_format($targetPct, 1) ?>%
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
