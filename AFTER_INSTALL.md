## Create default task user. 

PaymentHub integration can automatically create tasks. But every task need to be assigned to someone. For this we need
default user for tasks. 

1. Follow tutorial for user creation - https://www.orocrm.com/documentation/2.0/admin-guide/user-management/user-management-users#create-a-user
2. Use this username: default_tasks_user (PH\PaymentHubBundle\Entity\UserInterface::DEFAULT_TASKS_USER_NAME)

## Create email template for customer data update

1. Follow instruction from oro tutorial - https://www.orocrm.com/documentation/2.0/admin-guide/email/email-templates
2. As an entity select "Payment Hub Customer"
3. Update data link: ``<a href="http://taz-crm.dev/{{ system.appURL }}/customer/edit?token={{ entity.customerUpdateToken }}">{{ system.appURL }}/customer/edit?token={{ entity.customerUpdateToken }}</a>`` (paste it in source view) 

## Create tasks assigned to Default User

1. Create default user (follow "Create default task user" instructions) or create new user for `unassigned` tasks.
2. To to Main menu -> Activities -> Tasks.
3. Click on Create Task button.
4. Create new task and assign it to your Default Task User.

## Setup custom cron jobs

1. Run command `oro:cron:definitions:load` to setup all crons in scheduler
2. In your crontab file you need to add `*/1 * * * * /path/to/php /path/to/app/console oro:cron --env=prod > /dev/null`