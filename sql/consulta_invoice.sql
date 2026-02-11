SELECT p.name, a.authorization_number,a.authorization_date, i.invoice_number,i.amount,i.status,i.payment_date
FROM `patients` p 
inner join authorizations a
on a.patient_id = p.id
inner join invoices i
on a.id = i.authorization_id
WHERE p.id = 32
order by i.payment_date asc;