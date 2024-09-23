<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            ["title" => "Afghan Afghani", "code" => "AFA", "sign" => "؋"],
            ["title" => "Albanian Lek", "code" => "ALL", "sign" => "L"],
            ["title" => "Algerian Dinar", "code" => "DZD", "sign" => "دج"],
            ["title" => "Angolan Kwanza", "code" => "AOA", "sign" => "Kz"],
            ["title" => "Argentine Peso", "code" => "ARS", "sign" => "$"],
            ["title" => "Armenian Dram", "code" => "AMD", "sign" => "֏"],
            ["title" => "Aruban Florin", "code" => "AWG", "sign" => "ƒ"],
            ["title" => "Australian Dollar", "code" => "AUD", "sign" => "A$"],
            ["title" => "Azerbaijani Manat", "code" => "AZN", "sign" => "₼"],
            ["title" => "Bahamian Dollar", "code" => "BSD", "sign" => "B$"],
            ["title" => "Bahraini Dinar", "code" => "BHD", "sign" => ".د.ب"],
            ["title" => "Bangladeshi Taka", "code" => "BDT", "sign" => "৳"],
            ["title" => "Barbadian Dollar", "code" => "BBD", "sign" => "Bds$"],
            ["title" => "Belarusian Ruble", "code" => "BYR", "sign" => "Br"],
            ["title" => "Belgian Franc", "code" => "BEF", "sign" => "Fr"],
            ["title" => "Belize Dollar", "code" => "BZD", "sign" => "BZ$"],
            ["title" => "Bermudan Dollar", "code" => "BMD", "sign" => "$"],
            ["title" => "Bhutanese Ngultrum", "code" => "BTN", "sign" => "Nu."],
            ["title" => "Bitcoin", "code" => "BTC", "sign" => "₿"],
            ["title" => "Bolivian Boliviano", "code" => "BOB", "sign" => "Bs."],
            ["title" => "Bosnia-Herzegovina Convertible Mark", "code" => "BAM", "sign" => "KM"],
            ["title" => "Botswanan Pula", "code" => "BWP", "sign" => "P"],
            ["title" => "Brazilian Real", "code" => "BRL", "sign" => "R$"],
            ["title" => "British Pound Sterling", "code" => "GBP", "sign" => "£"],
            ["title" => "Brunei Dollar", "code" => "BND", "sign" => "B$"],
            ["title" => "Bulgarian Lev", "code" => "BGN", "sign" => "лв"],
            ["title" => "Burundian Franc", "code" => "BIF", "sign" => "FBu"],
            ["title" => "Cambodian Riel", "code" => "KHR", "sign" => "៛"],
            ["title" => "Canadian Dollar", "code" => "CAD", "sign" => "C$"],
            ["title" => "Cape Verdean Escudo", "code" => "CVE", "sign" => "$"],
            ["title" => "Cayman Islands Dollar", "code" => "KYD", "sign" => "$"],
            ["title" => "CFA Franc BCEAO", "code" => "XOF", "sign" => "CFA"],
            ["title" => "CFA Franc BEAC", "code" => "XAF", "sign" => "FCFA"],
            ["title" => "CFP Franc", "code" => "XPF", "sign" => "₣"],
            ["title" => "Chilean Peso", "code" => "CLP", "sign" => "$"],
            ["title" => "Chilean Unit of Account", "code" => "CLF", "sign" => "UF"],
            ["title" => "Chinese Yuan", "code" => "CNY", "sign" => "¥"],
            ["title" => "Colombian Peso", "code" => "COP", "sign" => "$"],
            ["title" => "Comorian Franc", "code" => "KMF", "sign" => "CF"],
            ["title" => "Congolese Franc", "code" => "CDF", "sign" => "FC"],
            ["title" => "Costa Rican Colón", "code" => "CRC", "sign" => "₡"],
            ["title" => "Croatian Kuna", "code" => "HRK", "sign" => "kn"],
            ["title" => "Cuban Convertible Peso", "code" => "CUC", "sign" => "$"],
            ["title" => "Czech Republic Koruna", "code" => "CZK", "sign" => "Kč"],
            ["title" => "Danish Krone", "code" => "DKK", "sign" => "kr"],
            ["title" => "Djiboutian Franc", "code" => "DJF", "sign" => "Fdj"],
            ["title" => "Dominican Peso", "code" => "DOP", "sign" => "$"],
            ["title" => "East Caribbean Dollar", "code" => "XCD", "sign" => "$"],
            ["title" => "Egyptian Pound", "code" => "EGP", "sign" => "£"],
            ["title" => "Eritrean Nakfa", "code" => "ERN", "sign" => "Nfk"],
            ["title" => "Estonian Kroon", "code" => "EEK", "sign" => "kr"],
            ["title" => "Ethiopian Birr", "code" => "ETB", "sign" => "Br"],
            ["title" => "Euro", "code" => "EUR", "sign" => "€"],
            ["title" => "Falkland Islands Pound", "code" => "FKP", "sign" => "£"],
            ["title" => "Fijian Dollar", "code" => "FJD", "sign" => "FJ$"],
            ["title" => "Gambian Dalasi", "code" => "GMD", "sign" => "D"],
            ["title" => "Georgian Lari", "code" => "GEL", "sign" => "₾"],
            ["title" => "German Mark", "code" => "DEM", "sign" => "DM"],
            ["title" => "Ghanaian Cedi", "code" => "GHS", "sign" => "GH₵"],
            ["title" => "Gibraltar Pound", "code" => "GIP", "sign" => "£"],
            ["title" => "Greek Drachma", "code" => "GRD", "sign" => "₯"],
            ["title" => "Guatemalan Quetzal", "code" => "GTQ", "sign" => "Q"],
            ["title" => "Guinean Franc", "code" => "GNF", "sign" => "FG"],
            ["title" => "Guyanaese Dollar", "code" => "GYD", "sign" => "G$"],
            ["title" => "Haitian Gourde", "code" => "HTG", "sign" => "G"],
            ["title" => "Honduran Lempira", "code" => "HNL", "sign" => "L"],
            ["title" => "Hong Kong Dollar", "code" => "HKD", "sign" => "HK$"],
            ["title" => "Hungarian Forint", "code" => "HUF", "sign" => "Ft"],
            ["title" => "Icelandic Króna", "code" => "ISK", "sign" => "kr"],
            ["title" => "Indian Rupee", "code" => "INR", "sign" => "₹"],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
