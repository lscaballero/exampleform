<?php

namespace Drupal\form_exsis\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @form
 * Make a input in \Drupal\form_exsis\From\FormExsisExample
 */

class FormExsisExample extends FormBase
{

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['example'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Example Form'),
        ];

        $form['example']['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
            '#title_display' => 'invisible',
            '#placeholder' => 'Nombre',
            '#id' => 'name',
            '#size' => 40,
            '#maxlength' => 60,
            '#weight' => 1,
        ];

        $form['example']['document'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Documento'),
            '#title_display' => 'invisible',
            '#placeholder' => 'Documento',
            '#id' => 'document',
            '#size' => 40,
            '#maxlength' => 30,
            '#weight' => 2,
        ];

        $form['example']['date'] = [
            '#type' => 'date',
            '#title' => $this->t('Date'),
            '#default_value' => '2020-012-18',
            '#weight' => 3,
        ];

        $form['example']['title'] = [
            '#type' => 'select',
            '#title' => $this
                ->t('Select element'),
            '#options' => [
                'Administrador' => $this->t('Administrador'),
                'product owner' => $this->t('Product owner'),
                'scrum master' => $this->t('Scrum master'),
            ],
            '#weight' => 4,
        ];

        $form['example']['actions'] = [
            '#type' => 'actions',
        ];

        $form['example']['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Enviar'),
            '#id' => 'search_save_id',
        ];

        return $form;
    }

    public function getFormId()
    {
        return 'form_exsis_example';
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $connection = \Drupal::database();
        $messenger = \Drupal::messenger();
        $name = $form_state->getValue('name');
        $document = $form_state->getValue('document');
        $date = $form_state->getValue('date');
        // $timestamp = strtotime($date);
        $title = $form_state->getValue('title');
        if ($title === 'Administrador') {
            $state = 1;
        } else {
            $state = 0;
        }

        if (isset($name) && isset($document) &&  isset($date) && isset($title)) {
            $connection->insert('example_users')
                ->fields([
                    'exsis_name' => $name,
                    'exsis_identification' => $document,
                    'exsis_date' => $date,
                    'exsis_title' => $title,
                    'exsis_estate' => $state,
                ])->execute();

            $messenger->addMessage($this->t('The dates is send correctly'), $messenger::TYPE_STATUS);
        } else {
            $messenger->addMessage($this->t('There are one error'), $messenger::TYPE_ERROR);
        }
    }
}
