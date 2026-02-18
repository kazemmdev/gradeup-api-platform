## How payment with gradeup works

1. in tenant config there's is an item declared as payment mod with values 'self' or 'gradeup'
2. if payment.mode = "self" then it works as is (user must have a valid merchant key with zarinpal)
3. if payment.mode = "gradeup" then it will:
    3.1. request to zarinpal with callbackurl like `https://gradeup.app/client/{tetant_id}/checkout?id={transaction_id}&program={plan_id}&session_id={user_id}`
    3.2. is_self(boolean) = false (default = true), and rest of data is filledout as before then redirect uer back to the app from tenant id