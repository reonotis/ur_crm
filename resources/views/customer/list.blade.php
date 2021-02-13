
@extends('layouts.app')

@section('content')





<div class="">
    <h2>顧客一覧</h2>
    <div class="basicData">
        <table class="customerListTable">
            <tr>
                <th>会員番号</th>
                <th>名前</th>
                <th>担当インストラクター</th>
                <th>ステータス</th>
                <th>最終受講日</th>
            </tr>
            <?php foreach ($customers as $key => $value) {
                ?>
                    <tr>
                        <td><?= $value->menberNumber ?></td>
                        <td>
                            <a href="{{route('customer.display', ['id' => $value->id ] )}}">
                                <?= $value->name ?> (<span class="nameRead"> <?= $value->read ?> </span>)
                            </a>
                        </td>
                        <td><?= $value->instName ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php
            }?>
        </table>
        {{ $customers->links() }}

    </div>
</div>

@endsection



<script>



</script>