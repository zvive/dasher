@php
$url = $getUrl();
@endphp

<x-dasher::stats.card :tag="$url ? 'a' : 'div'" :chart="$getChart()" :chart-color="$getChartColor()"
  :color="$getColor()" :icon="$getIcon()" :description="$getDescription()"
  :description-color="$getDescriptionColor()" :description-icon="$getDescriptionIcon()" :href="$url"
  :target="$shouldOpenUrlInNewTab() ? '_blank' : null" :label="$getLabel()" :value="$getValue()"
  class="dasher-stats-overview-widget-card" />
