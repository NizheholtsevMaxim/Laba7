$(document).ready(function () {

  $('form#response-html').submit(function (e) {
    e.preventDefault();
    var $name = $('select[name="name"]').val();
    var $start_date = $('#start_date').val();
    var $end_date = $('#end_date').val();
    var $author = $('select[name="author"]').val();

    $.ajax({
      url: 'result.php', /* Куда пойдет запрос */
      method: 'post', /* Метод передачи (post или get) */
      dataType: 'html', /* Тип данных в ответе (xml, json, script, html). */
      data: {
        name: $name,
        start_date: $start_date,
        end_date: $end_date,
        author: $author,
      }, /* Параметры передаваемые в запросе. */
      success: function (data) {   /* функция которая будет выполнена после успешного запроса.  */

        $('#previous-results').html(data);
      }
    });
    return false;
  });
//--------------------------------


  //JSON Response
  $('form#response-json').submit(function (e) {
    e.preventDefault();
    var $name = $('select[name="name"]').val();
    var $start_date = $('#start_date').val();
    var $end_date = $('#end_date').val();
    var $author = $('select[name="author"]').val();

    $.ajax({
      url: 'json.php', /* Куда пойдет запрос */
      method: 'post', /* Метод передачи (post или get) */
      dataType: 'json', /* Тип данных в ответе (xml, json, script, html). */
      data: {
        name: $name,
        start_date: $start_date,
        end_date: $end_date,
        author: $author,
      }, /* Параметры передаваемые в запросе. */
      success: function (data) {   /* функция которая будет выполнена после успешного запроса.  */
        var $html = ` <table>
                  <thead>
                    <tr>
                      <td>Название</td>
                      <td>ISBN</td>
                      <td>Издание</td>
                      <td>Год выхода</td>
                      <td>Кол-во страниц</td>
                      <td>Автор</td>
                      <td>Тип издания</td>
                    </tr>
                  </thead>
                  <tbody>`;
        for (var i = 0; i < data.length; i++) {
          console.log(data[i].name);
          $html += `
          <tr>
                        <td>` + data[i].name + `</td>
                        <td>` + data[i].ISBN + `</td>
                        <td>` + data[i].publisher + `</td>
                        <td>` + data[i].year + `</td>
                        <td>` + data[i].quantity + `</td>
                        <td>` + data[i].author + `</td>
                        <td>` + data[i].literature + `</td>
                      </tr>
`;
        }
        $html += `</tbody>
                </table>`;
        $('#previous-results').html($html);
      }
    });
    return false;
  });
//--------------------------------

  //XML Response
  $('form#response-xml').submit(function (e) {
    e.preventDefault();
    var $name = $('select[name="name"]').val();
    var $start_date = $('#start_date').val();
    var $end_date = $('#end_date').val();
    var $author = $('select[name="author"]').val();

    $.ajax({
      url: 'create_xml.php', /* Куда пойдет запрос */
      method: 'post', /* Метод передачи (post или get) */
      //dataType: 'xml', /* Тип данных в ответе (xml, json, script, html). */
      data: {
        name: $name,
        start_date: $start_date,
        end_date: $end_date,
        author: $author,
      }, /* Параметры передаваемые в запросе. */
      success: function (data) {   /* функция которая будет выполнена после успешного запроса.  */

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'response.xml', true);

// If specified, responseType must be empty string or "document"
        xhr.responseType = 'document';

// overrideMimeType() can be used to force the response to be parsed as XML
        xhr.overrideMimeType('text/xml');

        xhr.onload = function () {
          if (xhr.readyState === xhr.DONE) {
            if (xhr.status === 200) {

              var $xml = xhr.responseXML;

              var $html = ` <table>
                  <thead>
                    <tr>
                      <td>Название</td>
                      <td>ISBN</td>
                      <td>Издание</td>
                      <td>Год выхода</td>
                      <td>Кол-во страниц</td>
                      <td>Автор</td>
                      <td>Тип издания</td>
                    </tr>
                  </thead>
                  <tbody>`;
              $($xml).find('book').each(function () {
                $html += `
          <tr>
                        <td>` + $(this).find('name').html() + `</td>
                        <td>` + $(this).find('ISBN').html() + `</td>
                        <td>` + $(this).find('publisher').html() + `</td>
                        <td>` + $(this).find('year').html() + `</td>
                        <td>` + $(this).find('quantity').html() + `</td>
                        <td>` + $(this).find('author').html() + `</td>
                        <td>` + $(this).find('literature').html() + `</td>
                      </tr>
`;
              });
              $html += `</tbody>
                </table>`;
              $('#previous-results').html($html);

            }
          }
        };

        xhr.send(null);

      }
    });
    return false;
  });

});

