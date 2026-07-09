<?php
// resources/views/pages/dashboard/⚡sample.blade.php
// Route: Route::livewire('/dashboard-sample', 'pages::dashboard.sample');

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    protected int $perPage = 8;

    #[Computed]
    public function stats(): array
    {
        return [
            ['title' => 'Revenue', 'value' => '$48,290', 'trend' => '+12.4%', 'trendUp' => true],
            ['title' => 'Orders', 'value' => '1,204', 'trend' => '+4.1%', 'trendUp' => true],
            ['title' => 'Refund Rate', 'value' => '2.3%', 'trend' => '-0.6%', 'trendUp' => true],
            ['title' => 'Active Customers', 'value' => '832', 'trend' => '-1.8%', 'trendUp' => false],
        ];
    }

    #[Computed]
    public function paginator(): LengthAwarePaginator
    {
        $all = $this->sampleTransactions();

        $page = $this->getPage();
        $slice = $all->slice(($page - 1) * $this->perPage, $this->perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $all->count(),
            $this->perPage,
            $page,
            ['pageName' => 'page']
        );
    }

    #[Computed]
    public function rows()
    {
        return $this->paginator->items();
    }

    private function sampleTransactions(): \Illuminate\Support\Collection
    {
        $customers = ['Ana Reyes', 'Mark Villanueva', 'Kristine Uy', 'Jomari Santos', 'Bea Lopez', 'Carlo Dizon', 'Nica Fernandez', 'Paolo Cruz', 'Trisha Gomez', 'Elton Sy'];
        $products = ['Wireless Mouse', 'Mechanical Keyboard', 'USB-C Hub', 'Laptop Stand', 'Webcam HD', 'Noise-Cancelling Headset', 'Portable SSD 1TB', 'Monitor Arm', 'Bluetooth Speaker', 'Ergonomic Chair'];
        $statuses = [
            ['label' => 'Paid', 'color' => 'green'],
            ['label' => 'Pending', 'color' => 'amber'],
            ['label' => 'Refunded', 'color' => 'red'],
            ['label' => 'Processing', 'color' => 'blue'],
        ];

        return collect(range(1, 42))->map(function (int $i) use ($customers, $products, $statuses) {
            $status = $statuses[$i % count($statuses)];

            return [
                'id' => 1000 + $i,
                'date' => Carbon::now()->subDays($i)->format('M d, Y'),
                'status' => $status['label'],
                'status_color' => $status['color'],
                'customer' => $customers[$i % count($customers)],
                'purchase' => $products[$i % count($products)],
                'amount' => '$' . number_format(mt_rand(500, 25000) / 100, 2),
            ];
        });
    }
};
?>

<div>
 
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2">
                    <flux:select size="sm" class="">
                        <flux:select.option>Last 7 days</flux:select.option>
                        <flux:select.option>Last 14 days</flux:select.option>
                        <flux:select.option selected>Last 30 days</flux:select.option>
                        <flux:select.option>Last 60 days</flux:select.option>
                        <flux:select.option>Last 90 days</flux:select.option>
                    </flux:select>
                    <flux:subheading class="max-md:hidden whitespace-nowrap">compared to</flux:subheading>
                    <flux:select size="sm" class="max-md:hidden">
                        <flux:select.option selected>Previous period</flux:select.option>
                        <flux:select.option>Same period last year</flux:select.option>
                        <flux:select.option>Last month</flux:select.option>
                        <flux:select.option>Last quarter</flux:select.option>
                        <flux:select.option>Last 6 months</flux:select.option>
                        <flux:select.option>Last 12 months</flux:select.option>
                    </flux:select>
                </div>
                <flux:separator vertical class="max-lg:hidden mx-2 my-2" />
                <div class="max-lg:hidden flex justify-start items-center gap-2">
                    <flux:subheading class="whitespace-nowrap">Filter by:</flux:subheading>
                    <flux:badge as="button" rounded color="zinc" icon="plus" size="lg">Amount</flux:badge>
                    <flux:badge as="button" rounded color="zinc" icon="plus" size="lg" class="max-md:hidden">Status</flux:badge>
                    <flux:badge as="button" rounded color="zinc" icon="plus" size="lg">More filters...</flux:badge>
                </div>
            </div> 
        </div>

        <div class="flex gap-6 mb-6">
            @foreach ($this->stats as $stat)
                <div wire:key="stat-{{ $loop->index }}" class="relative flex-1 rounded-lg p-4 bg-zinc-50 dark:bg-zinc-700 {{ $loop->iteration > 1 ? 'max-md:hidden' : '' }} {{ $loop->iteration > 3 ? 'max-lg:hidden' : '' }}">
                    <flux:subheading>{{ $stat['title'] }}</flux:subheading>
                    <flux:heading size="xl" class="mb-2">{{ $stat['value'] }}</flux:heading>
                    <div class="flex items-center gap-1 font-medium text-sm @if ($stat['trendUp']) text-green-600 dark:text-green-400 @else text-red-500 dark:text-red-400 @endif">
                        <flux:icon :icon="$stat['trendUp'] ? 'arrow-trending-up' : 'arrow-trending-down'" variant="micro" /> {{ $stat['trend'] }}
                    </div>
                    <div class="absolute top-0 right-0 mr-2 mt-2">
                        <flux:button icon="ellipsis-horizontal" variant="subtle" size="sm" />
                    </div>
                </div>
            @endforeach
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column></flux:table.column>
                <flux:table.column class="max-md:hidden">ID</flux:table.column>
                <flux:table.column class="max-md:hidden">Date</flux:table.column>
                <flux:table.column class="max-md:hidden">Status</flux:table.column>
                <flux:table.column><span class="max-md:hidden">Customer</span><div class="md:hidden w-6"></div></flux:table.column>
                <flux:table.column>Purchase</flux:table.column>
                <flux:table.column>Revenue</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach ($this->rows as $row)
                    <flux:table.row wire:key="row-{{ $row['id'] }}">
                        <flux:table.cell class="pr-2"><flux:checkbox /></flux:table.cell>
                        <flux:table.cell class="max-md:hidden">#{{ $row['id'] }}</flux:table.cell>
                        <flux:table.cell class="max-md:hidden">{{ $row['date'] }}</flux:table.cell>
                        <flux:table.cell class="max-md:hidden"><flux:badge :color="$row['status_color']" size="sm" inset="top bottom">{{ $row['status'] }}</flux:badge></flux:table.cell>
                        <flux:table.cell class="min-w-6">
                            <div class="flex items-center gap-2">
                                <flux:avatar src="https://i.pravatar.cc/48?img={{ $row['id'] % 70 }}" size="xs" />
                                <span class="max-md:hidden">{{ $row['customer'] }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell class="max-w-6 truncate">{{ $row['purchase'] }}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $row['amount'] }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown position="bottom" align="end" offset="-15">
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                                <flux:menu>
                                    <flux:menu.item icon="document-text">View invoice</flux:menu.item>
                                    <flux:menu.item icon="receipt-refund">Refund</flux:menu.item>
                                    <flux:menu.item icon="archive-box" variant="danger">Archive</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <flux:pagination :paginator="$this->paginator" /> 
</div>
