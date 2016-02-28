@extends('laradmin::layout')

@section('page-title')
Linkwise Merchants Search
@stop

@section('page-subtitle')
dashboard subtitle, some description must be here
@stop

@section('breadcrumb')
@parent
<li class="active">Linkwise Merchants Search</li>
@stop

@section('page-menu')
@stop

@section('styles')
<!-- DATA TABLES -->
<link href="{{ Config::get('laradmin::general.asset_path') }}/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
@stop

@section('scripts')
<!-- DATA TABES SCRIPT -->
<script src="{{ Config::get('laradmin::general.asset_path') }}/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="{{ Config::get('laradmin::general.asset_path') }}/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#example1").dataTable();
    });
</script>
@stop

@section('content')

<div class="row">
    <div class="col-md-3">
        <form action="{{ route('utilities.linkwise.merchants') }}" method="get">
        <ul class="list-unstyled inline">
            <li>
                <label><input type="radio" name="scope" value="contains" checked="checked"> Περιέχει</label>
            </li>
            <li>
                <label><input type="radio" name="scope" value="begins"> Ξεκινάει με</label>
            </li>
        </ul>
        <input type="text" class="form-control" id="keyword" name="search" placeholder="Πληκτρολογήστε όνομα προγράμματος..." autocomplete="off">
        <ul class="list-unstyled inline">
            <li><label><input type="checkbox" id="r4" name="has_datafeed" value="1"> Με data feed</label></li>
        </ul>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Ταξίδια
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <ul class="list-unstyled clearfix">
                            <li>
                                <label><input type="checkbox" name="categories[1][25]" id="check_0" value="25">Αεροπορικά Εισιτήρια</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[1][29]" id="check_0" value="29">Ακτοπλοϊκά Εισιτήρια</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[1][63]" id="check_0" value="63">Είδη Ταξιδίου</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[1][147]" id="check_0" value="147">Ενοικιάσεις Αυτοκινήτων</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[1][89]" id="check_0" value="89">Κρουαζιέρες</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[1][99]" id="check_0" value="99">Ξενοδοχεία</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[1][109]" id="check_0" value="109">Πακέτα Ταξιδίων</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Μόδα &amp; Ομορφιά
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <ul class="list-unstyled clearfix">
                            <li>
                                <label><input type="checkbox" name="categories[2][3]" id="check_1" value="3">Fitness / Υγεία</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][27]" id="check_1" value="27">Αθλητικά Είδη</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][33]" id="check_1" value="33">Αξεσουάρ</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][81]" id="check_1" value="81">Είδη Φαρμακείου</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][67]" id="check_1" value="67">Ένδυση</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][73]" id="check_1" value="73">Εσώρουχα</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][87]" id="check_1" value="87">Κοσμήματα</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][103]" id="check_1" value="103">Οπτικά</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][105]" id="check_1" value="105">Παιδικά Ρούχα / Παπούτσια</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][111]" id="check_1" value="111">Παπούτσια</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][117]" id="check_1" value="117">Προσωπική Φροντίδα / Καλλυντικά</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[2][119]" id="check_1" value="119">Ρολόγια</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Προϊόντα
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">
                        <ul class="list-unstyled clearfix">
                            <li>
                                <label><input type="checkbox" name="categories[3][5]" id="check_2" value="5">Gadgets</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][21]" id="check_2" value="21">Αγροτικά Προϊόντα</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][31]" id="check_2" value="31">Αναλώσιμα</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][43]" id="check_2" value="43">Αυτοκίνητα / Μηχανές</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][45]" id="check_2" value="45">Βιβλία / CD / DVD</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][137]" id="check_2" value="137">Γαμήλια / Βαπτιστικά</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][57]" id="check_2" value="57">Είδη Supermarket</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][59]" id="check_2" value="59">Είδη Δώρων</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][61]" id="check_2" value="61">Είδη Καπνιστού</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][69]" id="check_2" value="69"  >Είδη Σπιτιού / Κήπος / DIY</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][75]" id="check_2" value="75">Η/Υ / Ηλεκτρονικά</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][77]" id="check_2" value="77">Ηλεκτρικές Συσκευές</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][83]" id="check_2" value="83">Κατοικίδια / Ζώα</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][91]" id="check_2" value="91">Λουλούδια</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][97]" id="check_2" value="97">Μουσική / Εξοπλισμός</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][107]" id="check_2" value="107">Παιχνίδια</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][113]" id="check_2" value="113">Πολυκαταστήματα</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][115]" id="check_2" value="115">Προσφορές / Κουπόνια</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][127]" id="check_2" value="127">Συλλογές / Συλλέκτες</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[3][131]" id="check_2" value="131">Φαγητό / Ποτό</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFour">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Τράπεζες &amp; Ασφάλειες
                        </a>
                    </h4>
                </div>
                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                    <div class="panel-body">
                        <ul class="list-unstyled clearfix">
                            <li>
                                <label><input type="checkbox" name="categories[21][35]" id="check_3" value="35">Ασφάλειες Αυτοκινήτου</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[21][37]" id="check_3" value="37">Ασφάλειες Ζωής</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[21][39]" id="check_3" value="39">Ασφάλειες Μηχανής</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[21][41]" id="check_3" value="41">Ασφάλειες Σπιτιού</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[21][101]" id="check_3" value="101">Οικονομικές Υπηρεσίες</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[21][129]" id="check_3" value="129">Τραπεζικά Προϊόντα</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingFive">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Υπηρεσίες
                        </a>
                    </h4>
                </div>
                <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                    <div class="panel-body">
                        <ul class="list-unstyled clearfix">
                            <li>
                                <label><input type="checkbox" name="categories[23][1]" id="check_4" value="1">Browser Games</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][9]" id="check_4" value="9">Hosting / Domains</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][15]" id="check_4" value="15">ISP</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][19]" id="check_4" value="19">Αγγελίες </label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][47]" id="check_4" value="47">Γάμος / Βάπτιση</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][49]" id="check_4" value="49">Δημοπρασίες</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][150]" id="check_4" value="150">Διαγωνισμοί</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][51]" id="check_4" value="51">Διακόσμηση</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][53]" id="check_4" value="53">Διασκέδαση / Ψυχαγωγία</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][55]" id="check_4" value="55">Διαφήμιση / Marketing</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][65]" id="check_4" value="65">Εκπαίδευση</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][133]" id="check_4" value="133">Ενέργεια / Φωτοβολταϊκά</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][148]" id="check_4" value="148">Εργασία</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][71]" id="check_4" value="71">Εστίαση / Delivery</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][79]" id="check_4" value="79">Καζίνο</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][85]" id="check_4" value="85">Κινητή / Σταθερή τηλεφωνία</label>
                            </li>
                            <li>
                                <label><input type="checkbox" name="categories[23][123]" id="check_4" value="123">Στοίχημα</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <button type="submit" class="btn btn-default btn-block">{{trans('laradmin::actions.search')}}</button>
        </form>
    </div>
    <div class="col-md-9">

    <div class="box box-primary">
    <div class="box-body table-responsive">

    <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>ConvRate</th>
            <th>EPC</th>
            <th>Comm1 %</th>
            <th>Comm2 %</th>
            <th>Joined</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($merchants as $merchant)
        <tr>
            <td>{{ $merchant['id'] }}</td>
            <td>
                <a href="{{$merchant['url']}}" target="_blank">
                    <img src="{{$merchant['logo']}}" border="0"/><br/>
                    {{ $merchant['name'] }}
                </a>
            </td>
            <td>{{ $merchant['conv_rate'] }}</td>
            <td>{{ $merchant['epc'] }}</td>
            <td>{{ str_ireplace('%', '', @explode(' - ', $merchant['commissions']['summary']['percent'])[0])*1 }}</td>
            <td>{{ str_ireplace('%', '', @explode(' - ', $merchant['commissions']['summary']['percent'])[1])*1 }}</td>
            <td>{{ (fn_is_empty($merchant['registration_date'])) ? '<i class="fa fa-fw fa-square-o"></i>' : '<i class="fa fa-fw fa-check-square-o"></i>' }}</td>
            <td>
                <a class="btn btn-default" href="{{route('utilities.linkwise.merchants.add', [$merchant['id']])}}"><i class="fa fa-plus"></i> {{trans('laradmin::actions.add')}}</a>
                @var $lm = Merchant::where('network_campaign_id', '=', $merchant['id'])->first()
                @if ($lm)
                <a class="btn btn-default" href="{{route('merchant.update', [$lm->id])}}"><i class="fa fa-edit "></i> {{trans('laradmin::actions.edit')}}</a>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>ConvRate</th>
            <th>EPC</th>
            <th>Comm1 %</th>
            <th>Comm2 %</th>
            <th>Joined</th>
            <th>&nbsp;</th>
        </tr>
        </tfoot>
    </table>

    </div>
    </div>

    </div>
</div>


@stop