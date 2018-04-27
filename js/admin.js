/* jshint -W098 */
function showDetails(id) {

    (function ($) {
        // wrapping this becuase wordpress uses jQuery in compatibility mode

        var txnDetails = {
            "action": "transaction_detail",
            "id": id
        };

        $.ajax({
            url: trans_url,
            type: "post",
            data: txnDetails,
            dataType: "html",
            cache: false,
            beforeSend: function () {
                // clear dialog div of old contents
                $('#dialog').html('');
                // show overlay
                $.isLoading({text: "Loading Order Data  "});
            },
            complete: function () {
                $.isLoading("hide");
            },
            success: function (response) {
                var wWidth = $(window).width();
                var dWidth = wWidth * 0.9;
                var wHeight = $(window).height();
                var dHeight = wHeight * 0.9;
                $('#dialog')
                    .html(response)
                    .dialog({
                        dialogClass: "no-close",
                        width: 'auto',
                        maxWidth: dWidth,
                        maxHeight: dHeight,
                        modal: true,
                        buttons: [
                            {
                                text: "Close",
                                click: function () {
                                    $(this).dialog("close");
                                }
                            }
                        ]
                    });
            }
        });

    })(jQuery);

}

function showConfirmation() {
    (function ($) {

        $("#dialogConfirm").dialog({
            buttons: {
                "Confirm": function () {
                    //document.clearent_clear_log.submit();
                    clearLog(true);
                },
                "Cancel": function () {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialogConfirm").dialog("open");
    })(jQuery);
}

function clearLog(confirm) {
    if (confirm) {
        document.clearent_clear_log.submit();
    }
}

(function ($) {
    $(document).ready(function () {
        $("#dialogConfirm").dialog({
            autoOpen: false,
            modal: true
        });
    });
})(jQuery);

