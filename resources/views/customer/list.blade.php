@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">顧客一覧</div>
                <div class="card-body">





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
        </div>
    </div>
</div>
@endsection



<script>



</script>