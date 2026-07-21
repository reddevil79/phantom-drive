<div class="table-responsive {!! (! $birthdays->isEmpty() ? 'panel-scroll' : '')  !!}">
    <table class="table table-hover">
        @forelse($birthdays as $birthday)
            <tr>
                <?php
                $images = $birthday->getMedia('profile');
                $generic = $birthday->gender == 'm' ? asset('assets/img/web/male.png') : asset('assets/img/web/female.png');
                $profileImage = ($images->isEmpty() ? $generic : url($images[0]->getUrl('thumb')));
                ?>
                <td><a href="{{ action('MembersController@show',['id' => $birthday->id]) }}"><img
                                src="{{ $profileImage }}" width="50px"/></a></td>
                <td><a href="{{ action('MembersController@show',['id' => $birthday->id]) }}">{{ $birthday->name }}</a></td>
                <td>{{ $birthday->contact }}</td>
                <td>{{ $birthday->DOB }}</td>
            </tr>
        @empty
            <div class="tab-empty-panel font-size-24 color-grey-300">
                No Data
            </div>
        @endforelse
    </table>
</div>
