## How payment with gradeup works

1. In the tenant configuration, there’s a payment.mode field with possible values: 'self' or 'gradeup'.
2. If payment.mode = "self"
    - The payment works as usual: the user must have a valid merchant key with Zarinpal.
    - is_internal = true (default).
3. If payment.mode = "gradeup"
    - A request is sent to Zarinpal with a callback URL like: `https://gradeup.app/client/{tetant_id}/checkout?id={transaction_id}&program={plan_id}`
    - After the transaction, the user is redirected back to the app associated with the tenant ID.
    - is_internal = false. All other payment data is filled as before.