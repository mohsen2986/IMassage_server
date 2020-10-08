<?php

use App\FilledForm;
use App\FilledQuestion;
use App\Form;
use App\Massage;
use App\Offers;
use App\Order;
use App\Packages;
use App\Question;
use App\QuestionType;
use App\ReservedTimeDates;
use App\Transactions;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // $this->call(UserSeeder::class);
        User::truncate();
        FilledForm::truncate();
        FilledQuestion::truncate();
        Form::truncate();
        Massage::truncate();
        Offers::truncate();
        Order::truncate();
        Packages::truncate();
        QuestionType::truncate();
        Question::truncate();
        ReservedTimeDates::truncate();
        Transactions::truncate();

        User::flushEventListeners();
        Form::flushEventListeners();
        FilledForm::flushEventListeners();
        FilledQuestion::flushEventListeners();
        Massage::flushEventListeners();
        Offers::flushEventListeners();
        Order::flushEventListeners();
        Packages::flushEventListeners();
        QuestionType::flushEventListeners();
        Question::flushEventListeners();
        ReservedTimeDates::flushEventListeners();
        Transactions::flushEventListeners();

        $usersQuantity = 100;
        $filledFormQuantity = 200;
        $filledQuestionQuantity = 100;
        $formQuantity = 10;
        $massageQuantity = 10;
        $offersQuantity = 10;
        $orderQuantity = 100;
        $packagesQuantity = 20;
        $questionTypeQuantity = 2;
        $questionQuantity = 10;
        $reservedTimeDateQuantity = 20;
        $transactionsQuantity = 20;

        factory(User::class, $usersQuantity)->create();
        factory(Form::class, $formQuantity)->create();
        factory(QuestionType::class, $questionTypeQuantity)->create();
        factory(Question::class, $questionQuantity)->create();
        factory(FilledQuestion::class, $filledQuestionQuantity)->create();
        factory(FilledForm::class, $filledFormQuantity)->create();
        factory(Massage::class, $massageQuantity)->create();
        factory(Offers::class, $offersQuantity)->create();
        factory(Packages::class, $packagesQuantity)->create();
        factory(ReservedTimeDates::class, $reservedTimeDateQuantity)->create();
        factory(Transactions::class, $transactionsQuantity)->create();
        factory(Order::class, $orderQuantity)->create();
    }
}
