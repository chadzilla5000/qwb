


        /*! jQuery JSON plugin v2.6.0 | github.com/Krinkle/jquery-json */
        !function (a) { "function" == typeof define && define.amd ? define(["jquery"], a) : a("object" == typeof exports ? require("jquery") : jQuery) }(function ($) { "use strict"; var escape = /["\\\x00-\x1f\x7f-\x9f]/g, meta = { "\b": "\\b", "\t": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", '"': '\\"', "\\": "\\\\" }, hasOwn = Object.prototype.hasOwnProperty; $.toJSON = "object" == typeof JSON && JSON.stringify ? JSON.stringify : function (a) { if (null === a) return "null"; var b, c, d, e, f = $.type(a); if ("undefined" !== f) { if ("number" === f || "boolean" === f) return String(a); if ("string" === f) return $.quoteString(a); if ("function" == typeof a.toJSON) return $.toJSON(a.toJSON()); if ("date" === f) { var g = a.getUTCMonth() + 1, h = a.getUTCDate(), i = a.getUTCFullYear(), j = a.getUTCHours(), k = a.getUTCMinutes(), l = a.getUTCSeconds(), m = a.getUTCMilliseconds(); return g < 10 && (g = "0" + g), h < 10 && (h = "0" + h), j < 10 && (j = "0" + j), k < 10 && (k = "0" + k), l < 10 && (l = "0" + l), m < 100 && (m = "0" + m), m < 10 && (m = "0" + m), '"' + i + "-" + g + "-" + h + "T" + j + ":" + k + ":" + l + "." + m + 'Z"' } if (b = [], $.isArray(a)) { for (c = 0; c < a.length; c++)b.push($.toJSON(a[c]) || "null"); return "[" + b.join(",") + "]" } if ("object" == typeof a) { for (c in a) if (hasOwn.call(a, c)) { if (f = typeof c, "number" === f) d = '"' + c + '"'; else { if ("string" !== f) continue; d = $.quoteString(c) } f = typeof a[c], "function" !== f && "undefined" !== f && (e = $.toJSON(a[c]), b.push(d + ":" + e)) } return "{" + b.join(",") + "}" } } }, $.evalJSON = "object" == typeof JSON && JSON.parse ? JSON.parse : function (str) { return eval("(" + str + ")") }, $.secureEvalJSON = "object" == typeof JSON && JSON.parse ? JSON.parse : function (str) { var filtered = str.replace(/\\["\\\/bfnrtu]/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""); if (/^[\],:{}\s]*$/.test(filtered)) return eval("(" + str + ")"); throw new SyntaxError("Error parsing JSON, source is not valid.") }, $.quoteString = function (a) { return a.match(escape) ? '"' + a.replace(escape, function (a) { var b = meta[a]; return "string" == typeof b ? b : (b = a.charCodeAt(), "\\u00" + Math.floor(b / 16).toString(16) + (b % 16).toString(16)) }) + '"' : '"' + a + '"' } });

        //////////////////////////////////////////////////////////////////////////////////////////
        // Вызов команды на сервере --------------------------------------------------------------
        //////////////////////////////////////////////////////////////////////////////////////////
        var Device = 0; // Номер устройства
        var UrlServer = "http://localhost:5893/"; // HTTP адрес сервера торгового оборудования, если пусто то локальный вызов
        var User = "User"; // Пользователь доступа к серверу торгового оборудования
        var Password = ""; // Пароль доступа к серверу торгового оборудования

        var Old_RRNCode = ""; // Для Экв. тер.
        var Old_AuthorizationCode = ""; // Для Экв. тер.

        function ExecuteCommand(Data, timeout) {

            $('.Responce').html("");

//            UrlServer = $("#SetServer").val();
            if (UrlServer == "AddIn") {

                KkmServer.Execute(function (message) {
                    ExecuteSuccess(message, {}, {});
                }, Data);

            } else {

                if (timeout == undefined) {
                    timeout = 60000; //Минута - некоторые драйверы при работе выполняют интерактивные действия с пользователем - тогда увеличте тайм-аут.
                }

                var JSon;
                if (typeof (Data) == "string") {
                    JSon = Data;
                } else {
                    JSon = $.toJSON(Data);
                };

                $.support.cors = true;
                var jqXHRvar = $.ajax({
                    type: 'POST',
                    async: true,
                    timeout: timeout,
                    url: UrlServer + ((UrlServer == "") ? window.location.protocol + "//" + window.location.host + "/" : "/") + 'Execute',
                    crossDomain: true,
                    dataType: 'json',
                    contentType: 'application/json; charset=UTF-8',
                    processData: false,
                    data: JSon,
                    headers: (User !== "" || Password !== "") ? { "Authorization": "Basic " + btoa(User + ":" + Password) } : "",
                    success: ExecuteSuccess,
                    error: ErrorSuccess
                });
            };
        }

        // Функция вызываемая после обработки команды - обработка возвращаемых данных
        function ExecuteSuccess(Rezult, textStatus, jqXHR) {

            //----------------------------------------------------------------------
            // ОБЩЕЕ
            //----------------------------------------------------------------------

            var Responce = "";
//            document.getElementById('Slip').textContent = "";

            if (Rezult.Status == 0) {
                Responce = Responce + "Статус: " + "Ok" + "\r\n";
            } else if (Rezult.Status == 1) {
                Responce = Responce + "Статус: " + "Выполняется" + "\r\n";
            } else if (Rezult.Status == 2) {
                Responce = Responce + "Статус: " + "Ошибка!" + "\r\n";
            } else if (Rezult.Status == 3) {
                Responce = Responce + "Статус: " + "Данные не найдены!" + "\r\n";
            };

            // Текст ошибки
            if (Rezult.Error != undefined && Rezult.Error != "") {
                Responce = Responce + "Ошибка: " + Rezult.Error + "\r\n";
            }

            if (Rezult != undefined) {
                var JSon = JSON.stringify(Rezult, "", 4);
                Responce = Responce + "JSON ответа: \r\n" + JSon + "\r\n";
                if (Rezult.Slip != undefined) {
//                    document.getElementById('Slip').textContent = Rezult.Slip;
                }
            }

//            document.getElementById('Responce').textContent = Responce;
            //$(".Responce").text(Responce);
        }

        // Функция вызываемая при ошибке передачи данных
        function ErrorSuccess(jqXHR, textStatus, errorThrown) {
//            document.getElementById('Responce').innerHTML = "Ошибка передачи данных по HTTP протоколу: " + errorThrown;
            //$('.Responce').html("Ошибка передачи данных по HTTP протоколу");
        }

        //////////////////////////////////////////////////////////////////////////////////////////
        //  КОМАНДЫ ФИСКАЛЬНЫХ РЕГИСТРАТОРОВ
        //////////////////////////////////////////////////////////////////////////////////////////



        // Пример печати  произвольного текста (Слип-чека)
        function PrintSlip(NumDevice, IsBarCode, ms) {

			var arr=ms.split("-.-");
//alert(arr[6]); return;
			var ttlamt = arr[8]*arr[9];

            // Подготовка данных команды
            var Data = {
                Command: "RegisterCheck",
                NumDevice: NumDevice,
                IsFiscalCheck: false,
                NotPrint: false,
                IdCommand: guid(),

                // Строки чека
                CheckStrings: [
                    //При вставке в текст символов ">#10#<" строка при печати выровнеется по центру, где 10 - это на сколько меньше станет строка ККТ
                    // {
                        // PrintText: {
                            // Text: ">#2#<ООО \"Рога и копыта\"",
                            // Font: 1,
                        // },
                    // },
                    // // При вставке в текст в середину строки символов "<#10#>" Левая часть строки будет выравнена по левому краю, правая по правому, где 10 - это на сколько меньше станет строка ККТ
                    // // При вставке в текст в середину строки символов "<#10#>>" Левая часть строки будет выравнена по правому краю, правая по правому, где 10 - отступ от правого клая
                    // { PrintText: { Text: "<<->>" }, },
                    // { PrintText: { Text: "Пример №1 печати поля:<#16#>154,41" }, },
                    { PrintText: { Text: "Заказ: "+arr[1] }, },
                    { PrintText: { Text: "Артикул: "+arr[5] }, },
                    { PrintText: { Text: "Этап: "+arr[3]+" - "+arr[6] }, },
                    { PrintText: { Text: "Процесс: "+arr[7] }, },
                    { PrintText: { Text: "Кол-во: "+arr[8]+" X "+arr[9]+" = "+ttlamt }, },
                    { PrintText: { Text: arr[0] }, },
					
                    // { PrintText: { Text: "<<->>" }, },
                    // { PrintText: { Text: "Пример №2 печати поля:<#8#>>4,00" }, },
                    //{ PrintText: { Text: "2-рое поле:<#8#>>1544,00" }, },
                    { PrintText: { Text: "<<->>" }, },
                    // Строка с печатью штрих-кода
                    {
                        BarCode: {
                            // Тип штрих-кода: "EAN8", "EAN13", "CODE39", "QR", "PDF417".
                            BarcodeType: "CODE128",
                            // Значение штрих-кода
                            Barcode: arr[0],
                        },
                    },
                    // { PrintText: { Text: "<<->>" }, },
                    // // Строка с печатью текста определенным шрифтом
                    // {
                        // PrintText: {
                            // Text: "Шрифт № 1",
                            // Font: 1, // 1-4, 0 - по настройкам ККМ
                            // Intensity: 15, // 1-15, 0 - по настройкам ККМ
                        // },
                    // },
                    // {
                        // PrintText: {
                            // Text: "Шрифт № 2",
                            // Font: 2, // 1-4, 0 - по настройкам ККМ
                            // Intensity: 10, // 1-15, 0 - по настройкам ККМ
                        // },
                    // },
                    // {
                        // PrintText: {
                            // Text: "Шрифт № 3",
                            // //Text: "Это мега крутой товар. Продается во всех магазинах страны. Покупайте только у нас",
                            // Font: 3, // 1-4, 0 - по настройкам ККМ
                            // Intensity: 5, // 1-15, 0 - по настройкам ККМ
                        // },
                    // },
                    // {
                        // PrintText: {
                            // Text: "Шрифт № 4",
                            // Font: 4, // 1-4, 0 - по настройкам ККМ
                            // Intensity: 0, // 1-15, 0 - по настройкам ККМ
                        // },
                    // },
                    // {
                        // BarCode: {
                            // // Тип штрих-кода: "EAN8", "EAN13", "CODE39", "QR", "PDF417".
                            // BarcodeType: "QR",
                            // // Значение штрих-кода
                            // Barcode: "12345DFG",
                        // },
                    // },
                ],
            };

            //Если чек без ШК то удаляес строку с ШК
            if (IsBarCode == false) {
                //Data.Cash = 100;
                for (var i = 0; i < Data.CheckStrings.length; i++) {
                    if (Data.CheckStrings[i] != undefined && Data.CheckStrings[i].BarCode != undefined) {
                        Data.CheckStrings[i].BarCode = null;
                    }
                }
            }

            // Вызов команды
            ExecuteCommand(Data);
        }


		
function PrintPrsBC(NumDevice, IsBarCode, ms) {
	var arr=ms.split("-.-");
//alert(arr[1]); return;
	var Data = {
		Command: "RegisterCheck",
		NumDevice: NumDevice,
		IsFiscalCheck: false,
		NotPrint: false,
		IdCommand: guid(),

		CheckStrings: [
			{ PrintText: { Text: ""+arr[1] }, },
			{ PrintText: { Text: "<<->>" }, },
			{
				BarCode: {
					BarcodeType: "CODE128",
					Barcode: arr[0],
					},
				},
			],
		};
	ExecuteCommand(Data);
	}
		




		
        // Открыть смену
        function OpenShift(NumDevice) {

            // Подготовка данных команды
            var Data = {
                // Команда серверу
                Command: "OpenShift",
                // Номер устройства. Если 0 то первое не блокированное на сервере
                NumDevice: NumDevice,
                // Id устройства. Строка. Если = "" то первое не блокированное на сервере
                IdDevice: "",
                // Продавец, тег ОФД 1021
                CashierName: "Kазакова Н.А.",
                // ИНН продавца тег ОФД 1203
                CashierVATIN: "430601071197",
                // Не печатать чек на бумагу
                NotPrint: false,
                // Уникальный идентификатор команды. Любая строока из 40 символов - должна быть уникальна для каждой подаваемой команды
                // По этому идентификатору можно запросить результат выполнения команды
                IdCommand: guid(),
            };
            // Вызов команды
            ExecuteCommand(Data);

            // Возвращается JSON:
            //{
            //    "CheckNumber": 1,    // Номер документа
            //    "SessionNumber": 23, // Номер смены
            //    "QRCode": "t=20170904T141100&fn=9999078900002287&i=108&fp=605445600",
            //    "Command": "OpenShift",
            //    "Error": "",  // Текст ошибки если была - обязательно показать пользователю - по содержанию ошибки можно в 90% случаях понять как ее устранять
            //    "Status": 0   // Ok = 0, Run(Запущено на выполнение) = 1, Error = 2, NotFound(устройство не найдено) = 3, NotRun = 4
            //}

        }


        // Асинхронная проверка выполнения команды!!
        function GetRezult(IdCommand) {

            // Заново запрашиваем результат выполнения команды
            var Data = {
                // Команда серверу - запрос выволнеия команды
                Command: "GetRezult",
                // Уникальный идентификатор ранее поданной команды
                IdCommand: IdCommand,
            };

            // Вызываем запрос на получение результата с задержкой 2 секунды
            ExecuteCommand(Data);

        };

        //////////////////////////////////////////////////////////////////////////////////////////
        //  КОМАНДЫ МЕНЕДЖЕРА СЕРВЕРА
        //////////////////////////////////////////////////////////////////////////////////////////


        // Включение/Выключение ККМ
        function OnOffUnut(NumDevice) {

            // Подготовка данных команды
            var Data = {
                // Команда серверу
                Command: "OnOffUnut",
                // Номер устройства. Если 0 то любое
                NumDevice: NumDevice,
                // Id устройства. Строка. Если = "" то любое
                IdDevice: "",
                // Включение/Выключение
                Active: false,
            };

            // Вызов команды
            ExecuteCommand(Data);

        }

        // Получение списка ККМ
        function ExecuteJSON() {
            ExecuteCommand($("#JSON").val());
        }

        //////////////////////////////////////////////////////////////////////////////////////////
        //  ВСПОМОГАТЕЛЬНОЕ
        //////////////////////////////////////////////////////////////////////////////////////////

        // Герерация GUID
        function guid() {

            function S4() {
                return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
            }

            return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
        }

        // Расчет ключа для получения суб-лицензии
        function GetKeySubLicensing(Email, Password) {

            // ВНИМАНИЕ!
            // Расчет ключа для получения суб-лицензии должен происходить на сервере (а не у чужого клиента-суб-лицензиата)
            // НЕ передавайте свой пароль клиенту-суб-лицензиата
            // Данный пример только для понимания как гереировать ключ на Вашем сервере!!

            //хеш пароля
            var Hash1 = md5(Password).toUpperCase();

            // солим
            var Hash2 = md5(Hash1 + "Qwerty").toUpperCase();

            // формируем дату
            var now = new Date();
            var formated_date = "" + now.getFullYear() + ((now.getMonth() + 1) < 10 ? "0" : "") + (now.getMonth() + 1) + (now.getDate() < 10 ? "0" : "") + now.getDate();
            //now.getMonth()+1 потому что getMonth() возвращает 0..11 а не 1..12

            //добавляем данные лицензии
            var Hash3 = md5(Hash2 + formated_date).toUpperCase();

            // Имя машины или имя клиента max 100 символов
            // Указывать не обязательно
            // Позволяет быстрее найти серийный номер в личном кабинете
            var Name = "Клиент-1";

            // формируем ключ
            if (Name == "") {
                var Key = Email + "/" + Hash3;
            } else {
                var Key = Name + ":" + Email + "/" + Hash3;
            };

            return Key;

        }

        // Вычисление хеша md5
        function md5(s) {

            return hex_md5(s);

            var hexcase = 0;   /* hex output format. 0 - lowercase; 1 - uppercase        */
            var b64pad = "";  /* base-64 pad character. "=" for strict RFC compliance   */

            /*
             * These are the functions you'll usually want to call
             * They take string arguments and return either hex or base-64 encoded strings
             */
            function hex_md5(s) { return rstr2hex(rstr_md5(str2rstr_utf8(s))); }
            function b64_md5(s) { return rstr2b64(rstr_md5(str2rstr_utf8(s))); }
            function any_md5(s, e) { return rstr2any(rstr_md5(str2rstr_utf8(s)), e); }
            function hex_hmac_md5(k, d)
            { return rstr2hex(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d))); }
            function b64_hmac_md5(k, d)
            { return rstr2b64(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d))); }
            function any_hmac_md5(k, d, e)
            { return rstr2any(rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d)), e); }

            /*
             * Perform a simple self-test to see if the VM is working
             */
            function md5_vm_test() {
                return hex_md5("abc").toLowerCase() == "900150983cd24fb0d6963f7d28e17f72";
            }

            /*
             * Calculate the MD5 of a raw string
             */
            function rstr_md5(s) {
                return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
            }

            /*
             * Calculate the HMAC-MD5, of a key and some data (raw strings)
             */
            function rstr_hmac_md5(key, data) {
                var bkey = rstr2binl(key);
                if (bkey.length > 16) bkey = binl_md5(bkey, key.length * 8);

                var ipad = Array(16), opad = Array(16);
                for (var i = 0; i < 16; i++) {
                    ipad[i] = bkey[i] ^ 0x36363636;
                    opad[i] = bkey[i] ^ 0x5C5C5C5C;
                }

                var hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
                return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
            }

            /*
             * Convert a raw string to a hex string
             */
            function rstr2hex(input) {
                try { hexcase } catch (e) { hexcase = 0; }
                var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
                var output = "";
                var x;
                for (var i = 0; i < input.length; i++) {
                    x = input.charCodeAt(i);
                    output += hex_tab.charAt((x >>> 4) & 0x0F)
                        + hex_tab.charAt(x & 0x0F);
                }
                return output;
            }

            /*
             * Convert a raw string to a base-64 string
             */
            function rstr2b64(input) {
                try { b64pad } catch (e) { b64pad = ''; }
                var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
                var output = "";
                var len = input.length;
                for (var i = 0; i < len; i += 3) {
                    var triplet = (input.charCodeAt(i) << 16)
                        | (i + 1 < len ? input.charCodeAt(i + 1) << 8 : 0)
                        | (i + 2 < len ? input.charCodeAt(i + 2) : 0);
                    for (var j = 0; j < 4; j++) {
                        if (i * 8 + j * 6 > input.length * 8) output += b64pad;
                        else output += tab.charAt((triplet >>> 6 * (3 - j)) & 0x3F);
                    }
                }
                return output;
            }

            /*
             * Convert a raw string to an arbitrary string encoding
             */
            function rstr2any(input, encoding) {
                var divisor = encoding.length;
                var i, j, q, x, quotient;

                /* Convert to an array of 16-bit big-endian values, forming the dividend */
                var dividend = Array(Math.ceil(input.length / 2));
                for (i = 0; i < dividend.length; i++) {
                    dividend[i] = (input.charCodeAt(i * 2) << 8) | input.charCodeAt(i * 2 + 1);
                }

                /*
                 * Repeatedly perform a long division. The binary array forms the dividend,
                 * the length of the encoding is the divisor. Once computed, the quotient
                 * forms the dividend for the next step. All remainders are stored for later
                 * use.
                 */
                var full_length = Math.ceil(input.length * 8 /
                    (Math.log(encoding.length) / Math.log(2)));
                var remainders = Array(full_length);
                for (j = 0; j < full_length; j++) {
                    quotient = Array();
                    x = 0;
                    for (i = 0; i < dividend.length; i++) {
                        x = (x << 16) + dividend[i];
                        q = Math.floor(x / divisor);
                        x -= q * divisor;
                        if (quotient.length > 0 || q > 0)
                            quotient[quotient.length] = q;
                    }
                    remainders[j] = x;
                    dividend = quotient;
                }

                /* Convert the remainders to the output string */
                var output = "";
                for (i = remainders.length - 1; i >= 0; i--)
                    output += encoding.charAt(remainders[i]);

                return output;
            }

            /*
             * Encode a string as utf-8.
             * For efficiency, this assumes the input is valid utf-16.
             */
            function str2rstr_utf8(input) {
                var output = "";
                var i = -1;
                var x, y;

                while (++i < input.length) {
                    /* Decode utf-16 surrogate pairs */
                    x = input.charCodeAt(i);
                    y = i + 1 < input.length ? input.charCodeAt(i + 1) : 0;
                    if (0xD800 <= x && x <= 0xDBFF && 0xDC00 <= y && y <= 0xDFFF) {
                        x = 0x10000 + ((x & 0x03FF) << 10) + (y & 0x03FF);
                        i++;
                    }

                    /* Encode output as utf-8 */
                    if (x <= 0x7F)
                        output += String.fromCharCode(x);
                    else if (x <= 0x7FF)
                        output += String.fromCharCode(0xC0 | ((x >>> 6) & 0x1F),
                            0x80 | (x & 0x3F));
                    else if (x <= 0xFFFF)
                        output += String.fromCharCode(0xE0 | ((x >>> 12) & 0x0F),
                            0x80 | ((x >>> 6) & 0x3F),
                            0x80 | (x & 0x3F));
                    else if (x <= 0x1FFFFF)
                        output += String.fromCharCode(0xF0 | ((x >>> 18) & 0x07),
                            0x80 | ((x >>> 12) & 0x3F),
                            0x80 | ((x >>> 6) & 0x3F),
                            0x80 | (x & 0x3F));
                }
                return output;
            }

            /*
             * Encode a string as utf-16
             */
            function str2rstr_utf16le(input) {
                var output = "";
                for (var i = 0; i < input.length; i++)
                    output += String.fromCharCode(input.charCodeAt(i) & 0xFF,
                        (input.charCodeAt(i) >>> 8) & 0xFF);
                return output;
            }

            function str2rstr_utf16be(input) {
                var output = "";
                for (var i = 0; i < input.length; i++)
                    output += String.fromCharCode((input.charCodeAt(i) >>> 8) & 0xFF,
                        input.charCodeAt(i) & 0xFF);
                return output;
            }

            /*
             * Convert a raw string to an array of little-endian words
             * Characters >255 have their high-byte silently ignored.
             */
            function rstr2binl(input) {
                var output = Array(input.length >> 2);
                for (var i = 0; i < output.length; i++)
                    output[i] = 0;
                for (var i = 0; i < input.length * 8; i += 8)
                    output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32);
                return output;
            }

            /*
             * Convert an array of little-endian words to a string
             */
            function binl2rstr(input) {
                var output = "";
                for (var i = 0; i < input.length * 32; i += 8)
                    output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF);
                return output;
            }

            /*
             * Calculate the MD5 of an array of little-endian words, and a bit length.
             */
            function binl_md5(x, len) {
                /* append padding */
                x[len >> 5] |= 0x80 << ((len) % 32);
                x[(((len + 64) >>> 9) << 4) + 14] = len;

                var a = 1732584193;
                var b = -271733879;
                var c = -1732584194;
                var d = 271733878;

                for (var i = 0; i < x.length; i += 16) {
                    var olda = a;
                    var oldb = b;
                    var oldc = c;
                    var oldd = d;

                    a = md5_ff(a, b, c, d, x[i + 0], 7, -680876936);
                    d = md5_ff(d, a, b, c, x[i + 1], 12, -389564586);
                    c = md5_ff(c, d, a, b, x[i + 2], 17, 606105819);
                    b = md5_ff(b, c, d, a, x[i + 3], 22, -1044525330);
                    a = md5_ff(a, b, c, d, x[i + 4], 7, -176418897);
                    d = md5_ff(d, a, b, c, x[i + 5], 12, 1200080426);
                    c = md5_ff(c, d, a, b, x[i + 6], 17, -1473231341);
                    b = md5_ff(b, c, d, a, x[i + 7], 22, -45705983);
                    a = md5_ff(a, b, c, d, x[i + 8], 7, 1770035416);
                    d = md5_ff(d, a, b, c, x[i + 9], 12, -1958414417);
                    c = md5_ff(c, d, a, b, x[i + 10], 17, -42063);
                    b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162);
                    a = md5_ff(a, b, c, d, x[i + 12], 7, 1804603682);
                    d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101);
                    c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290);
                    b = md5_ff(b, c, d, a, x[i + 15], 22, 1236535329);

                    a = md5_gg(a, b, c, d, x[i + 1], 5, -165796510);
                    d = md5_gg(d, a, b, c, x[i + 6], 9, -1069501632);
                    c = md5_gg(c, d, a, b, x[i + 11], 14, 643717713);
                    b = md5_gg(b, c, d, a, x[i + 0], 20, -373897302);
                    a = md5_gg(a, b, c, d, x[i + 5], 5, -701558691);
                    d = md5_gg(d, a, b, c, x[i + 10], 9, 38016083);
                    c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335);
                    b = md5_gg(b, c, d, a, x[i + 4], 20, -405537848);
                    a = md5_gg(a, b, c, d, x[i + 9], 5, 568446438);
                    d = md5_gg(d, a, b, c, x[i + 14], 9, -1019803690);
                    c = md5_gg(c, d, a, b, x[i + 3], 14, -187363961);
                    b = md5_gg(b, c, d, a, x[i + 8], 20, 1163531501);
                    a = md5_gg(a, b, c, d, x[i + 13], 5, -1444681467);
                    d = md5_gg(d, a, b, c, x[i + 2], 9, -51403784);
                    c = md5_gg(c, d, a, b, x[i + 7], 14, 1735328473);
                    b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734);

                    a = md5_hh(a, b, c, d, x[i + 5], 4, -378558);
                    d = md5_hh(d, a, b, c, x[i + 8], 11, -2022574463);
                    c = md5_hh(c, d, a, b, x[i + 11], 16, 1839030562);
                    b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556);
                    a = md5_hh(a, b, c, d, x[i + 1], 4, -1530992060);
                    d = md5_hh(d, a, b, c, x[i + 4], 11, 1272893353);
                    c = md5_hh(c, d, a, b, x[i + 7], 16, -155497632);
                    b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640);
                    a = md5_hh(a, b, c, d, x[i + 13], 4, 681279174);
                    d = md5_hh(d, a, b, c, x[i + 0], 11, -358537222);
                    c = md5_hh(c, d, a, b, x[i + 3], 16, -722521979);
                    b = md5_hh(b, c, d, a, x[i + 6], 23, 76029189);
                    a = md5_hh(a, b, c, d, x[i + 9], 4, -640364487);
                    d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835);
                    c = md5_hh(c, d, a, b, x[i + 15], 16, 530742520);
                    b = md5_hh(b, c, d, a, x[i + 2], 23, -995338651);

                    a = md5_ii(a, b, c, d, x[i + 0], 6, -198630844);
                    d = md5_ii(d, a, b, c, x[i + 7], 10, 1126891415);
                    c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905);
                    b = md5_ii(b, c, d, a, x[i + 5], 21, -57434055);
                    a = md5_ii(a, b, c, d, x[i + 12], 6, 1700485571);
                    d = md5_ii(d, a, b, c, x[i + 3], 10, -1894986606);
                    c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523);
                    b = md5_ii(b, c, d, a, x[i + 1], 21, -2054922799);
                    a = md5_ii(a, b, c, d, x[i + 8], 6, 1873313359);
                    d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744);
                    c = md5_ii(c, d, a, b, x[i + 6], 15, -1560198380);
                    b = md5_ii(b, c, d, a, x[i + 13], 21, 1309151649);
                    a = md5_ii(a, b, c, d, x[i + 4], 6, -145523070);
                    d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379);
                    c = md5_ii(c, d, a, b, x[i + 2], 15, 718787259);
                    b = md5_ii(b, c, d, a, x[i + 9], 21, -343485551);

                    a = safe_add(a, olda);
                    b = safe_add(b, oldb);
                    c = safe_add(c, oldc);
                    d = safe_add(d, oldd);
                }
                return Array(a, b, c, d);
            }

            /*
             * These functions implement the four basic operations the algorithm uses.
             */
            function md5_cmn(q, a, b, x, s, t) {
                return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b);
            }
            function md5_ff(a, b, c, d, x, s, t) {
                return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
            }
            function md5_gg(a, b, c, d, x, s, t) {
                return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
            }
            function md5_hh(a, b, c, d, x, s, t) {
                return md5_cmn(b ^ c ^ d, a, b, x, s, t);
            }
            function md5_ii(a, b, c, d, x, s, t) {
                return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
            }

            /*
             * Add integers, wrapping at 2^32. This uses 16-bit operations internally
             * to work around bugs in some JS interpreters.
             */
            function safe_add(x, y) {
                var lsw = (x & 0xFFFF) + (y & 0xFFFF);
                var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
                return (msw << 16) | (lsw & 0xFFFF);
            }

            /*
             * Bitwise rotate a 32-bit number to the left.
             */
            function bit_rol(num, cnt) {
                return (num << cnt) | (num >>> (32 - cnt));
            }
        }


		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		        //////////////////////////////////////////////////////////////////////////////////////////
        //  КОМАНДЫ ФИСКАЛЬНЫХ РЕГИСТРАТОРОВ
        //////////////////////////////////////////////////////////////////////////////////////////

        // Печать чеков
        function RegisterCheck(NumDevice, TypeCheck, IsBarCode, bc) {

            // Подготовка данных команды
            var Data = {
                // Команда серверу
                Command: "RegisterCheck",

                //***********************************************************************************************************
                // ПОЛЯ ПОИСКА УСТРОЙСТВА
                //***********************************************************************************************************
                // Номер устройства. Если 0 то первое не блокированное на сервере
                NumDevice: NumDevice,
                // ИНН ККМ для поиска. Если "" то ККМ ищется только по NumDevice,
                // Если NumDevice = 0 а InnKkm заполнено то ККМ ищется только по InnKkm
                InnKkm: "",
                //---------------------------------------------
                // Заводской номер ККМ для поиска. Если "" то ККМ ищется только по NumDevice,
                KktNumber: "",
                // **********************************************************************************************************

                // Время (сек) ожидания выполнения команды.
                //Если За это время команда не выполнилась в статусе вернется результат "NotRun" или "Run"
                //Проверить результат еще не выполненной команды можно командой "GetRezult"
                //Если не указано или 0 - то значение по умолчанию 60 сек.
                // Поле не обязательно. Это поле можно указывать во всех командах
                Timeout: 30,
                // Уникальный идентификатор команды. Любая строка из 40 символов - должна быть уникальна для каждой подаваемой команды
                // По этому идентификатору можно запросить результат выполнения команды
                // Поле не обязательно
                IdCommand: guid(),
                // Это фискальный или не фискальный чек
                IsFiscalCheck: true,
                // Тип чека;
                // 0 – продажа;                             10 – покупка;
                // 1 – возврат продажи;                     11 - возврат покупки;
                // 8 - продажа только по ЕГАИС (обычный чек ККМ не печатается)
                // 9 - возврат продажи только по ЕГАИС (обычный чек ККМ не печатается)
                TypeCheck: TypeCheck,
                // Не печатать чек на бумагу
                NotPrint: false, //true,
                // Количество копий документа
                NumberCopies: 0,
                // Продавец, тег ОФД 1021
                CashierName: "Kазакова Н.А.",
                // ИНН продавца тег ОФД 1203
                CashierVATIN: "430601071197",
                // Телефон или е-Майл покупателя, тег ОФД 1008
                // Если чек не печатается (NotPrint = true) то указывать обязательно
                // Формат: Телефон +{Ц} Email {С}@{C}
                ClientAddress: "client@server.ru",
                // Aдрес электронной почты отправителя чека тег ОФД 1117 (если задан при регистрации можно не указывать)
                // Формат: Email {С}@{C}
                SenderEmail: "sochi@mama.com",
                // Система налогообложения (СНО) применяемая для чека
                // Если не указанно - система СНО настроенная в ККМ по умолчанию
                // 0: Общая ОСН
                // 1: Упрощенная УСН (Доход)
                // 2: Упрощенная УСН (Доход минус Расход)
                // 3: Единый налог на вмененный доход ЕНВД
                // 4: Единый сельскохозяйственный налог ЕСН
                // 5: Патентная система налогообложения
                // Комбинация разных СНО не возможна
                // Надо указывать если ККМ настроена на несколько систем СНО
                TaxVariant: "",
                // Дополнительные произвольные реквизиты (не обязательно) пока только 1 строка
                AdditionalProps: [
                    //{ Print: true, PrintInHeader: false, NameProp: "Номер транзакции", Prop: "234/154" },
//                    { Print: true, PrintInHeader: false, NameProp: "Дата транзакции", Prop: "10.11.2016 10:30" },
                ],
                //ClientId: "557582273e4edc1c6f315efe",
                // Это только для тестов: Получение ключа суб-лицензии : ВНИМАНИЕ: ключ суб-лицензии вы должны генерить у себя на сервере!!!!
                //KeySubLicensing: GetKeySubLicensing("client@server.ru", "12qw12"),
                // КПП организации, нужно только для ЕГАИС
                //KPP: "782543005",

                // Строки чека
                CheckStrings: [
                    // Строка с печатью картинки
                    {
                        BarCode: {
                            // Тип штрих-кода: "EAN13", "CODE39", "CODE128", "QR", "PDF417".
//                            BarcodeType: "PDF417",
                            // Значение штрих-кода
//                            Barcode: "12345DFG Proba pera, Print barcode 1234567890.",


                            BarcodeType: "CODE128",
                            // Значение штрих-кода
                            Barcode: bc,
	
							
							

						},
                    },
                ],

                // // Наличная оплата (2 знака после запятой)
                // Cash: 800,
                // // Сумма электронной оплаты (2 знака после запятой)
                // ElectronicPayment: 0.01,
                // // Сумма из предоплаты (зачетом аванса) (2 знака после запятой)
                // AdvancePayment: 0,
                // // Сумма постоплатой(в кредит) (2 знака после запятой)
                // Credit: 0,
                // // Сумма оплаты встречным предоставлением (сертификаты, др. мат.ценности) (2 знака после запятой)
                // CashProvision: 0,

            };

            //Если чек без ШК то удаляем строку с ШК
            if (IsBarCode == false) {
                //Data.Cash = 100;
                for (var i = 0; i < Data.CheckStrings.length; i++) {
                    if (Data.CheckStrings[i] != undefined && Data.CheckStrings[i].BarCode != undefined) {
                        Data.CheckStrings[i].BarCode = null;
                    };
                    if (Data.CheckStrings[i] != undefined && Data.CheckStrings[i].PrintImage != undefined) {
                        Data.CheckStrings[i].PrintImage = null;
                    };
                };
            };

            //Скидываем данные об агенте - т.к.у Вас невярнека ККТ не зарегистрирована как Агент.
            for (var i = 0; i < Data.CheckStrings.length; i++) {
                if (Data.CheckStrings[i] != undefined && Data.CheckStrings[i].Register != undefined) {
                    Data.CheckStrings[i].Register.AgentSign = null;
                    Data.CheckStrings[i].Register.AgentData = null;
                    Data.CheckStrings[i].Register.PurveyorData = null;
                };
            };

            // Вызов команды
            ExecuteCommand(Data);

            // Возвращается JSON:
            //{
            //    "CheckNumber": 1,    // Номер документа
            //    "SessionNumber": 23, // Номер смены
            //    "URL": "https://ofd.ru/rec/7708806062/0000000006018032/9999078900002287/106/4160536402",
            //    "QRCode": "t=20170904T140900&s=0.01&fn=9999078900002287&i=106&fp=4160536402&n=1",
            //    "Command": "RegisterCheck",
            //    "Error": "",  // Текст ошибки если была - обязательно показать пользователю - по содержанию ошибки можно в 90% случаях понять как ее устранять
            //    "Status": 0   // Ok = 0, Run(Запущено на выполнение) = 1, Error = 2, NotFound(устройство не найдено) = 3, NotRun = 4
            //}

        }

		
		
		
		
		
		
		
