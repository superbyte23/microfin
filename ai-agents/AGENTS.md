# Project Context

## Livewire 4 SFC Setup
- **SFC location**: `resources/views/components/` (first entry in `component_locations`)
- **Page components**: `resources/views/pages/` via `pages::` namespace (from `component_namespaces`)
- **Layout**: `layouts::app` (configured as `component_layout`)
- **Islands**: `@island`/`@endisland` IS a Livewire 4 feature (not Volt-only)
- **`livewire:form`** exists (NOT `make:livewire-form`)
- **`#[Lazy]`** → LW4 uses `defer` attribute instead
- **`wire:key`** mandatory in all `@foreach` loops

## Coding Standards Reference
See `livewire-4-sfc-coding-standards.md` for full standards — it's accurate for this project's Livewire 4 config.
