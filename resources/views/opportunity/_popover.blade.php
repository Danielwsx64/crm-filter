<ul class='ls-no-list-style'>
  <li><b>Conta: </b>{{  format_info($opportunity->account) }}<br></li>

  <li><b>Contato: </b>{{  format_info($opportunity->contact_name . ' ' . $opportunity->contact_last_name) }}<br></li>

  <li><b>Rua: </b>{{ format_info($opportunity->street) }}<br></li>

  <li><b>Cidade: </b>{{ format_info($opportunity->city) }}<br></li>

  <li><b>Estado: </b>{{ format_info($opportunity->state) }}<br></li>

  <li><b>CEP: </b>{{ format_info($opportunity->postalcode) }}<br></li>

  <li><b>Tel: </b>{{ format_info($opportunity->phone) }}<br></li>

  <li><b>Celular: </b>{{ format_info($opportunity->celphone) }}<br></li>

  <li><b>Comercial: </b>{{ format_info($opportunity->work) }}<br></li>

  <li><b>Email: </b>{{ format_info($opportunity->email) }}<br></li>

  <li><b>Email alternativo: </b>{{ format_info($opportunity->alt_email) }}<br></li>

  <li><b>Corretor Responsável: </b>{{  format_info($opportunity->user_name . ' ' . $opportunity->user_last_name) }}<br></li>

  <li><b>Atribuido à: </b>{{  format_info($opportunity->assigned_name . ' ' .  $opportunity->assigned_last_name) }}<br></li>
</ul>
