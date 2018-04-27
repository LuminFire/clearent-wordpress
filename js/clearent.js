Clearent = {
    cardType: null,

    formatField: function (e, format) {
        var v;
        e = e || window.event;
        var el = e.target;
        // get raw numeric value
        v = el.value.replace(/[^0-9]+/g, "");
        switch (format) {
            case "card":
                if (v.substr(0, 2) === "34" || v.substr(0, 2) === "37") {
                    // amex #### ###### #%###
                    if (v.length > 10) {
                        v = v.split(/(.{4})(.{6})(.*)/).filter(String).join("  ");
                    } else if (v.length > 4) {
                        v = v.split(/(.{4})(.*)/).filter(String).join("  ");
                    }
                    //} else if (v.substr(0, 1) === "1") {
                    // token - don't format
                } else {
                    // others #### #### #### ####
                    v = v.split(/(....)/).filter(String).join("  ");
                }
                break;
            case "dateMMYY":
                v = v.substring(0, 4);
                v = v.split(/(..)/).filter(String).join(" / ");
                break;
            case "zip":
                v = v.split(/(.{5})(.*)/).filter(String).join("  ");
                break;
            default:
                break;
        }
        el.value = v;
    },

    isValidCard: function (card) {
        /*
         Luhn algorithm to validate credit card by check digit
         From the rightmost digit, which is the check digit, moving left, double the value of every second digit;
         if the product of this doubling operation is greater than 9 (e.g., 8 × 2 = 16), then
         sum the digits of the product (e.g., 16: 1 + 6 = 7, 18: 1 + 8 = 9).
         or simply subtract 9 from the product (e.g., 16: 16 - 9 = 7, 18: 18 - 9 = 9).
         Take the sum of all the digits.
         If the total modulo 10 is equal to 0 (if the total ends in zero) then the number is valid according to the Luhn formula; else it is not valid.
         */
        card = card.replace(/[^0-9]+/g, "");

        if (card.length === 0) {
            // nothing entered - ignore
            return true;
        }
        if (card.length < 13 || card.length > 19) {
            // invalid number
            // form.validate()
            return false;
        }

        var temp,
            total = 0,
            cardDigits = card.split(/(.)/).filter(isDigit);

        for (var j = 1, i = cardDigits.length - 1; i > -1; i--, j++) {
            // going in reverse, double every second digit
            if (j % 2 === 0) {
                temp = parseInt(cardDigits[i]) * 2;
                total += temp > 9 ? temp - 9 : temp;
            } else {
                total += parseInt(cardDigits[i]);
            }
        }

        return total % 10 === 0;

        function isDigit(value) {
            //return Number.isInteger(parseInt(value));
            return (/^\d+$/).test(value);
        }

    },

    isValidExpdate: function (expdate) {
        var d = new Date();
        var month = parseInt(expdate.substr(0, 2), 10);
        var year = parseInt(expdate.substr(2, 2), 10);
        var currentYear = parseInt((d.getFullYear() + "").substr(2, 2), 10);
        var currentMonth = parseInt(d.getMonth() + 1, 10);
        return (expdate.length === 4) && (year > currentYear || (month >= currentMonth && year >= currentYear));
    },

    isValidCVC: function (cvc, cardNum) {
        var cardType = Clearent.getCardType(cardNum);
        cardType = cardType.length > 0 ? cardType.toLowerCase() : cardType;
        switch (cardType) {
            case "visa":
            case "mc":
            case "discover":
            case "dinersclub":
            case "jcb":
                return cvc.length === 3;
            case "amex":
                return cvc.length === 4;
            default:
                return (cvc.length === 3 || cvc.length === 4);
        }
    },

    getCardTypeFromEvent: function (e) {
        e = e || window.event;
        var el = e.target;
        return Clearent.getCardType(el.value);
    },

    getCardType: function (cardNumber) {
        // get raw numeric value
        var v = cardNumber.replace(/[^0-9]+/g, "");
        var re = {
            visa: /^4[0-9]{6,}$/,
            mastercard: /^5[1-5][0-9]{5,}$/,
            amex: /^3[47][0-9]{5,}$/,
            discover: /^6(?:011|5[0-9]{2})[0-9]{3,}$/,
            dinersclub: /^3(?:0[0-5]|[68][0-9])[0-9]{4,}$/,
            jcb: /^(?:2131|1800|35[0-9]{3})[0-9]{3,}$/
        };
        var cardType;
        if (re.visa.test(v)) {
            cardType = "visa";
        } else if (re.mastercard.test(v)) {
            cardType = "mc";
        } else if (re.amex.test(v)) {
            cardType = "amex";
        } else if (re.discover.test(v)) {
            cardType = "discover";
        } else if (re.dinersclub.test(v)) {
            cardType = "dinersclub";
        } else if (re.jcb.test(v)) {
            cardType = "jcb";
        } else {
            cardType = "";
        }
        Clearent.setCardType(cardType);
        return cardType;
    },

    setCardType: function (cardType) {

        switch (cardType.toLowerCase()) {
            case "visa":
                Clearent.cardType = "VISA";
                break;
            case "mc":
            case "mastercard":
                Clearent.cardType = "MASTERCARD";
                break;
            case "amex":
            case "american express":
                Clearent.cardType = "AMERICAN EXPRESS";
                break;
            case "discover":
                Clearent.cardType = "DISCOVER";
                break;
            case "dinersclub":
            case "diners club":
                Clearent.cardType = "DISCOVER";  // run as discover
                break;
            case "jcb":
                Clearent.cardType = "DISCOVER";  // run as discover
                break;
            default:
                return undefined;
        }

    },

    setType: function (e, type) {
        e = e || window.event;
        var el = e.target;
        el.setAttribute("type", type);
    },

    pay: function () {

        (function ($) {
            // wrapping this becuase wordpress uses jQuery in compatibility mode

            var txnDetails = {
                "action": "transaction",
                "amount": $("#amount").val(),
                "card": $("#card").val(),
                "g-recaptcha-response": $("#g-recaptcha-response").val(),
                "expire-date-month": $("#expire-date-month").val(),
                "expire-date-year": $("#expire-date-year").val(),
                "csc": $("#csc").val(),
                //"isShippingSameAsBilling": $("#shipping").prop("checked"),
                "email": $("#email").val(),
                // transaction metadata
                "invoice": $("#invoice").val(),
                "purchase-order": $("#purchase-order").val(),
                "email-address": $("#email-address").val(),
                "customer-id": $("#customer-id").val(),
                "order-id": $("#order-id").val(),
                "client-ip": $("#client-ip").val(),
                "description": $("#description").val(),
                "comments": $("#comments").val(),
                // billing
                "billing-first-name": $("#billing-first-name").val(),
                "billing-last-name": $("#billing-last-name").val(),
                "billing-company": $("#billing-company").val(),
                "billing-street": $("#billing-street").val(),
                "billing-street2": $("#billing-street2").val(),
                "billing-city": $("#billing-city").val(),
                "billing-state": $("#billing-state").val(),
                "billing-zip": $("#billing-zip").val(),
                "billing-country": $("#billing-country").val(),
                "billing-phone": $("#billing-phone").val(),
                "billing-is-shipping": $("#billing-is-shipping:checked").val() || false,
                // shipping
                "shipping-first-name": $("#shipping-first-name").val(),
                "shipping-last-name": $("#shipping-last-name").val(),
                "shipping-company": $("#shipping-company").val(),
                "shipping-street": $("#shipping-street").val(),
                "shipping-street2": $("#shipping-street2").val(),
                "shipping-city": $("#shipping-city").val(),
                "shipping-state": $("#shipping-state").val(),
                "shipping-zip": $("#shipping-zip").val(),
                "shipping-country": $("#shipping-country").val(),
                "shipping-phone": $("#shipping-phone").val()
            };

            $.ajax({
                url: trans_url,
                type: "post",
                data: txnDetails,
                dataType: "json",
                cache: false,
                beforeSend: function () {
                    // clear errors
                    $("#errors").addClass("hidden");
                    $('#errors_message_bottom').addClass("hidden");
                    // show overlay
                    $.isLoading({text: "Processing Order  "});
                },
                complete: function () {
                    $.isLoading("hide");
                },
                success: function (response) {
                    if (response && response["error"]) {
                        $("#errors").removeClass("hidden");
                        $("#errors_message").html(response["error"]);
                        $('#errors_message_bottom').removeClass("hidden");
                        grecaptcha.reset();
                    }

                    if (response && response["redirect"]) {
                        window.location = response["redirect"];
                    }
                }
            });

        })(jQuery);

    }

};

(function ($) {
    // wrapping this becuase wordpress uses jQuery in compatibility mode

    $(document).ready(handler);

})(jQuery);


function handler() {

    (function ($) {
        // wrapping this because WordPress uses jQuery in compatibility mode

        $("#card")
            .on("input", function (event) {
                Clearent.formatField(event, "card");
                Clearent.getCardTypeFromEvent(event);
            })
            .on("focus", function (event) {
                Clearent.setType(event, "text");
            })
            .on("blur", function (event) {
                Clearent.setType(event, "password");
            });

        $("#csc")
            .on("focus", function (event) {
                Clearent.setType(event, "text");
            })
            .on("blur", function (event) {
                Clearent.setType(event, "password");
            });

        $(function () {
            var availableStates = [
                "Alabama",
                "Alaska",
                "Arizona",
                "Arkansas",
                "California",
                "Colorado",
                "Connecticut",
                "Delaware",
                "District Of Columbia",
                "Florida",
                "Georgia",
                "Hawaii",
                "Idaho",
                "Illinois",
                "Indiana",
                "Iowa",
                "Kansas",
                "Kentucky",
                "Louisiana",
                "Maine",
                "Maryland",
                "Massachusetts",
                "Michigan",
                "Minnesota",
                "Mississippi",
                "Missouri",
                "Montana",
                "Nebraska",
                "Nevada",
                "New Hampshire",
                "New Jersey",
                "New Mexico",
                "New York",
                "North Carolina",
                "North Dakota",
                "Ohio",
                "Oklahoma",
                "Oregon",
                "Pennsylvania",
                "Rhode Island",
                "South Carolina",
                "South Dakota",
                "Tennessee",
                "Texas",
                "Utah",
                "Vermont",
                "Virginia",
                "Washington",
                "West Virginia",
                "Wisconsin",
                "Wyoming"
            ];
            $("#billing-state").autocomplete({
                source: availableStates
            });
            $("#shipping-state").autocomplete({
                source: availableStates
            });

            var availableCountries = [
                "Afghanistan",
                "Albania",
                "Algeria",
                "Andorra",
                "Angola",
                "Antigua and Barbuda",
                "Argentina",
                "Armenia",
                "Australia",
                "Austria",
                "Azerbaijan",
                "Bahamas",
                "Bahrain",
                "Bangladesh",
                "Barbados",
                "Belarus",
                "Belgium",
                "Belize",
                "Benin",
                "Bhutan",
                "Bolivia",
                "Bosnia and Herzegovina",
                "Botswana",
                "Brazil",
                "Brunei",
                "Bulgaria",
                "Burkina Faso",
                "Burundi",
                "Cabo Verde",
                "Cambodia",
                "Cameroon",
                "Canada",
                "Central African Republic",
                "Chad",
                "Chile",
                "China",
                "Colombia",
                "Comoros",
                "Democratic Republic of the Congo",
                "Republic of the Congo",
                "Costa Rica",
                "Cote d'Ivoire",
                "Croatia",
                "Cuba",
                "Cyprus",
                "Czech Republic",
                "Denmark",
                "Djibouti",
                "Dominica",
                "Dominican Republic",
                "Ecuador",
                "Egypt",
                "El Salvador",
                "Equatorial Guinea",
                "Eritrea",
                "Estonia",
                "Ethiopia",
                "Fiji",
                "Finland",
                "France",
                "Gabon",
                "Gambia",
                "Georgia",
                "Germany",
                "Ghana",
                "Greece",
                "Grenada",
                "Guatemala",
                "Guinea",
                "Guinea-Bissau",
                "Guyana",
                "Haiti",
                "Honduras",
                "Hungary",
                "Iceland",
                "India",
                "Indonesia",
                "Iran",
                "Iraq",
                "Ireland",
                "Israel",
                "Italy",
                "Jamaica",
                "Japan",
                "Jordan",
                "Kazakhstan",
                "Kenya",
                "Kiribati",
                "Kosovo",
                "Kuwait",
                "Kyrgyzstan",
                "Laos",
                "Latvia",
                "Lebanon",
                "Lesotho",
                "Liberia",
                "Libya",
                "Liechtenstein",
                "Lithuania",
                "Luxembourg",
                "Macedonia",
                "Madagascar",
                "Malawi",
                "Malaysia",
                "Maldives",
                "Mali",
                "Malta",
                "Marshall Islands",
                "Mauritania",
                "Mauritius",
                "Mexico",
                "Micronesia",
                "Moldova",
                "Monaco",
                "Mongolia",
                "Montenegro",
                "Morocco",
                "Mozambique",
                "Myanmar (Burma)",
                "Namibia",
                "Nauru",
                "Nepal",
                "Netherlands",
                "New Zealand",
                "Nicaragua",
                "Niger",
                "Nigeria",
                "North Korea",
                "Norway",
                "Oman",
                "Pakistan",
                "Palau",
                "Palestine",
                "Panama",
                "Papua New Guinea",
                "Paraguay",
                "Peru",
                "Philippines",
                "Poland",
                "Portugal",
                "Qatar",
                "Romania",
                "Russia",
                "Rwanda",
                "Samoa",
                "San Marino",
                "Sao Tome and Principe",
                "Saudi Arabia",
                "Senegal",
                "Serbia",
                "Seychelles",
                "Sierra Leone",
                "Singapore",
                "Slovakia",
                "Slovenia",
                "Solomon Islands",
                "Somalia",
                "South Africa",
                "South Korea",
                "South Sudan",
                "Spain",
                "Sri Lanka",
                "St. Kitts and Nevis",
                "St. Lucia",
                "St. Vincent and The Grenadines",
                "Sudan",
                "Suriname",
                "Swaziland",
                "Sweden",
                "Switzerland",
                "Syria",
                "Taiwan",
                "Tajikistan",
                "Tanzania",
                "Thailand",
                "Timor-Leste",
                "Togo",
                "Tonga",
                "Trinidad and Tobago",
                "Tunisia",
                "Turkey",
                "Turkmenistan",
                "Tuvalu",
                "Uganda",
                "Ukraine",
                "United Arab Emirates",
                "United Kingdom",
                "United States of America",
                "Uruguay",
                "Uzbekistan",
                "Vanuatu",
                "Venezuela",
                "Vietnam",
                "Yemen",
                "Zambia",
                "Zimbabwe"
            ];

            $("#billing-country").autocomplete({
                source: availableCountries
            });
            $("#shipping-country").autocomplete({
                source: availableCountries
            });

        });

        $("#billing-is-shipping").bind("click", function () {
            var fields = [
                "shipping-first-name",
                "shipping-last-name",
                "shipping-company",
                "shipping-street",
                "shipping-street2",
                "shipping-city",
                "shipping-state",
                "shipping-zip",
                "shipping-country",
                "shipping-phone"
            ];

            var checked = $('#billing-is-shipping').is(":checked");
            for (var i = 0; i < fields.length; i++) {
                if (checked) {
                    // hide field
                    $('#' + fields[i]).closest('tr').addClass("hidden");
                } else {
                    // show field
                    $('#' + fields[i]).closest('tr').removeClass("hidden");
                }
            }

        });

    })(jQuery);

}
