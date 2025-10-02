<?php
// src/Form/TicketType.php
namespace App\Form;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $options['is_admin'];
        
        $builder
            ->add('author', EmailType::class, [
                'label' => 'Votre email',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Décrivez votre problème (20-250 caractères)'
                ]
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Incident' => 'Incident',
                    'Panne' => 'Panne',
                    'Évolution' => 'Évolution',
                    'Anomalie' => 'Anomalie',
                    'Information' => 'Information'
                ],
                'attr' => ['class' => 'form-control']
            ]);

        if ($isAdmin) {
            $builder
                ->add('status', ChoiceType::class, [
                    'label' => 'Statut',
                    'choices' => [
                        'Nouveau' => 'Nouveau',
                        'Ouvert' => 'Ouvert',
                        'Résolu' => 'Résolu',
                        'Fermé' => 'Fermé'
                    ],
                    'attr' => ['class' => 'form-control']
                ])
                ->add('responsible', EntityType::class, [
                    'label' => 'Responsable',
                    'class' => User::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.roles LIKE :role')
                            ->setParameter('role', '%ROLE_STAFF%')
                            ->orWhere('u.roles LIKE :admin')
                            ->setParameter('admin', '%ROLE_ADMIN%');
                    },
                    'required' => false,
                    'attr' => ['class' => 'form-control']
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'is_admin' => false,
        ]);
    }
}