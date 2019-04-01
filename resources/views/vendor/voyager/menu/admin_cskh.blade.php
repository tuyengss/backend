<ul class="nav navbar-nav">

@php
    if (Voyager::translatable($items)) {
        $items = $items->load('translations');
    }
@endphp

@foreach ($items as $item)
    @php
        $listItemClass = [];
        $styles = null;
        $linkAttributes = null;
        $transItem = $item;

        if (Voyager::translatable($item)) {
            $transItem = $item->translate($options->locale);
        }

        $href = $item->link();

        // Current page
        if(url($href) == url()->current()) {
            array_push($listItemClass, 'active');
        }

        $permission = '';
        $hasChildren = false;

        // With Children Attributes
        if(!$item->children->isEmpty())
        {
            foreach($item->children as $child)
            {
                $hasChildren = $hasChildren || Auth::user()->can('browse', $child);

                if(url($child->link()) == url()->current())
                {
                    array_push($listItemClass, 'active');
                }
            }
            if (!$hasChildren) {
                continue;
            }

            $linkAttributes = 'href="#' . $transItem->id .'-dropdown-element" data-toggle="collapse" aria-expanded="'. (in_array('active', $listItemClass) ? 'true' : 'false').'"';
            array_push($listItemClass, 'dropdown');
        }
        else
        {
            $linkAttributes =  'href="' . url($href) .'"';

            if(!Auth::user()->can('browse', $item)) {
                continue;
            }
        }
    @endphp
    <li class="{{ implode(" ", $listItemClass) }}">
        @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 4)
        <a {!! $linkAttributes !!} target="{{ $item->target }}" style="color:{{ (isset($item->color) && $item->color != '#000000' ? $item->color : '') }}">
            <span class="icon {{ $item->icon_class }}"></span>
            <span class="title">{{ $transItem->title }}</span>
        </a>
        @else
            @if($item->id !== 32)
                <a {!! $linkAttributes !!} target="{{ $item->target }}" style="color:{{ (isset($item->color) && $item->color != '#000000' ? $item->color : '') }}">
                    <span class="icon {{ $item->icon_class }}"></span>
                    <span class="title">{{ $transItem->title }}</span>
                </a>
            @endif
        @endif
        @if($hasChildren)
            <div id="{{ $transItem->id }}-dropdown-element" class="panel-collapse collapse {{ (in_array('active', $listItemClass) ? 'in' : '') }}">
                <div class="panel-body">
                    @include('menu.admin_menu', ['items' => $item->children, 'options' => $options, 'innerLoop' => true])
                </div>
            </div>
        @endif
    </li>
@endforeach

</ul>
