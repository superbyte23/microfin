# Coding Standards — Laravel 13 · Livewire 4 SFC · Flux UI v2

## 1. File Structure & Naming

- **Default to SFC.** Use `php artisan make:livewire` (SFC output) unless the component is genuinely large/JS-heavy — then use `--mfc`. Don't mix conventions within the same project without reason.
- **Naming**: kebab-case dotted names (`employee.create`, `invoice.line-item`). File path mirrors the dotted name under `resources/views/components/`.
- **Page components** live under `resources/views/pages/` and use the `pages::` namespace, routed via `Route::livewire()` — never a controller.
- **One component, one responsibility.** If a component's `<?php ?>` block exceeds ~150 lines or mixes unrelated concerns, split it (nested component, Form object, or Action class).

## 2. Component Anatomy

- **Exactly one root element** in the Blade half — non-negotiable.
- Order inside the `<?php ... ?>` block, top to bottom:
  1. `use` imports
  2. Traits (`use WithPagination;`, etc.)
  3. Public properties (grouped: state → then `#[Url]`/`#[Locked]` flagged ones)
  4. `mount()` (only if property auto-binding isn't sufficient)
  5. `#[Computed]` methods
  6. Actions (public methods callable from `wire:click`/`wire:submit`)
  7. `render()` only if you need explicit extra view data — otherwise omit it

## 3. Properties & Data Exposure

- Use **public properties** only for simple, directly-bound state (`wire:model` targets).
- Use **`#[Computed]`** for anything derived or queried — never run queries directly in `render()` or inline in the Blade view.
- Never expose secrets or internal-only data via public properties (they're serialized to the client snapshot). Use `protected`/`private` or a computed method that withholds sensitive fields.
- Type-hint properties (`public string $search = '';`) — improves IDE support and catches Livewire hydration mismatches early.
- Use `#[Locked]` on any public property the client must never be able to tamper with (e.g. an ID used for authorization checks).

## 4. Validation

- **Simple/one-off action validation** → inline `$this->validate([...])` inside the action method.
- **Real-time/live feedback** → `#[Validate('rule')]` attribute on the property.
- **Non-trivial or reused forms** → extract a Form object (`php artisan make:livewire-form`), inject as `public PostForm $form;`. Keep validation rules and casting inside the Form object, not the component.
- Always validate on the server side action, even if `#[Validate]` already gives live feedback — never trust client state alone.

## 5. Actions

- Action method names are verbs (`save`, `deactivate`, `addLineItem`) — no `handle`/`do` prefixes.
- Type-hint route-model-bound parameters directly (`public function deactivate(Employee $employee)`) instead of passing raw IDs and re-querying.
- Add `#[Renderless]` to actions that don't change any output the user needs to see immediately (e.g. logging, background dispatch) to skip the unnecessary re-render.
- Guard destructive actions with `wire:confirm` on the frontend **and** an authorization check (`$this->authorize(...)` or a Policy) server-side — never rely on the confirm dialog alone.

## 6. Blade / Flux UI Markup

- **Flux first.** Reach for `flux:button`, `flux:input`, `flux:field`, `flux:modal`, `flux:table`, etc. before hand-rolled Tailwind markup. Confirm free-vs-Pro tier before assuming a component is available.
- **`wire:key` is mandatory** on every element inside a loop that can reorder, grow, or shrink (list items, table rows, nested components).
- Use `wire:model.live.debounce.300ms` for search/filter inputs — plain `wire:model` (deferred) for form fields that submit as a batch.
- Keep Blade conditionals shallow; extract complex conditional blocks into a `#[Computed]` boolean or string rather than nesting `@if`/`@else` chains deeply in markup.

## 7. Events & Nesting

- Pass data **down** via props (`:prop="$value"`), send events **up** via `$dispatch()` + `#[On('event-name')]` — never reach into a parent/sibling's state directly.
- Mark child props `#[Reactive]` explicitly when the child must react to a parent's live-updated value; otherwise assume props are captured once at mount.
- Use Islands (`@island ... @endisland`) for sub-regions that update on a different cadence than the rest of the component (counters, polling widgets) instead of re-rendering the whole tree.

## 8. Performance

- Prefer `#[Computed]` (memoized per-request) over repeated calls to the same query/method in the view.
- Avoid heavy logic in `render()` — it runs on every single update/interaction.
- Use `#[Lazy]` for components that are expensive to render initially and can show a placeholder first (below-the-fold widgets, dashboards).
- Paginate any list-producing computed property with realistic data volume — don't load full tables into memory.

## 9. Testing

- Default to **Pest** with `Livewire::test('component.name')->set()->call()->assertSet()/assertSee()` patterns, unless the existing project uses PHPUnit-style Livewire tests — match what's already there.
- Generate the test alongside the component with `--test` when scaffolding new components.
- Test actions for both the happy path and authorization/validation failure paths.

## 10. Consistency Rule

- **Match existing project conventions first.** If a codebase already uses class-based components or a legacy Volt-package pattern, mirror that pattern rather than silently converting files to SFC — propose modernization separately instead of doing it inline with an unrelated task.
