<?php
  require_once('../../../vendor/autoload.php');
  require_once('../../../config/db.php');
  require_once('../../../database/pdo_db.php');
  require_once('../../../database/conectare.php');
  require_once('../../../app/models/Customer.php');
  require_once('../../../app/models/Transaction.php');

\Stripe\Stripe::setApiKey('sk_test_51Kcqy9LoeunozsDdpbF2f8XIkOz9HKgyP2Ahx4bfj5bjhIVHnu4F4jteAoyQLK1x9L81IUuaLdlfw1VnQRfy6EfM00WE9mmobh');

 $POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

 $first_name = $POST['first_name'];
 $last_name = $POST['last_name'];
 $email = $POST['email'];
 $amount = $POST['amount'];
 $token = $POST['stripeToken'];
 $f_id = $POST['fundraiser_id'];

$customer = \Stripe\Customer::create(array(
  "email" => $email,
  "source" => $token
));

$charge = \Stripe\Charge::create(array(
  "amount" => $amount*100,
  "currency" => "eur",
  "description" => "Donation",
  "customer" => $customer->id
));

$customerData = [
  'id' => $charge->customer,
  'first_name' => $first_name,
  'last_name' => $last_name,
  'email' => $email
];

$customer = new Customer();

$customer->addCustomer($customerData);

$transactionData = [
  'id' => $charge->id,
  'customer_id' => $charge->customer,
  'product' => $charge->description,
  'amount' => $charge->amount,
  'currency' => $charge->currency,
  'status' => $charge->status,
];

$transaction = new Transaction();

$transaction->addTransaction($transactionData);

$mysqli->query("INSERT INTO fundraising_transactions(fundraising_id, transaction_id, amount) VALUES($f_id, '$charge->id', ".round($charge->amount/100).")")or die("Could not save your transaction");

header('Location: success.php?tid='.$charge->id.'&product='.$charge->description);