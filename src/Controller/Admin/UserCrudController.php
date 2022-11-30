<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pseudo'),
            EmailField::new('email'),
            ArrayField::new('Roles')->hideOnForm(),
            IntegerField::new('age'),
            BooleanField::new('hide')
        ];
    }


    public function createEntity(string $entityFqcn)
    {
        $user = new User();
        return $user;
    }

    //CONFIGURE LES ACTIONS DES ENTITEES
    public function configureActions(Actions $actions): Actions
    {
        $upgrade = Action::new('upgrade', 'Upgrade Role')
        ->linkToCrudAction('upgrade')
        ->setCssClass('btn btn-dark')
        ->displayIf(static function ($user){
            if(in_array('ROLE_ADMIN', $user->getRoles())) return false;
            else return true;
        });

        $downgrade = Action::new('downgrade', 'Downgrade Role')
        ->linkToCrudAction('downgrade')->setCssClass('btn btn-dark')
        ->displayIf(static function ($user){
            if(in_array('ROLE_USER', $user->getRoles())) return false;
            else return true;
        });

        return $actions
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fas fa-pencil')->setLabel(false)->setCssClass('btn btn-primary');
            })
            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action->setIcon('fas fa-trash')->setLabel(false)->setCssClass('btn btn-danger');
            })
            ->add(Crud::PAGE_INDEX, $upgrade)
            ->add(Crud::PAGE_INDEX, $downgrade)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
        ;
    }

    public function downgrade(
        AdminContext $context, 
        UserRepository $userRepository): Response
    {

        $user = $context->getEntity()->getInstance();
        $userRoles = $user->getRoles();

        if (in_array("ROLE_ADMIN", $userRoles)) {
            $user->setRoles(['ROLE_MODERATOR']);
            //$this->addFlash('success', $user->getPseudo()." has been downgrade");
        }
        if (in_array("ROLE_MODERATOR", $userRoles)) {
            $user->setRoles(['ROLE_USER']);
            //$this->addFlash('success', $user->getPseudo()." has been downgrade");
        }

        $userRepository->save($user, true);

        return $this->redirect('/admin');
    }

    public function upgrade(
        AdminContext $context, 
        UserRepository $userRepository): Response
    {

        $user = $context->getEntity()->getInstance();
        $userRoles = $user->getRoles();

        if (in_array("ROLE_MODERATOR", $userRoles)) {
            $user->setRoles(['ROLE_ADMIN']);
            //$this->addFlash('success', $user->getPseudo()." has been upgrade");
        }
        if (in_array("ROLE_USER", $userRoles)) {
            $user->setRoles(['ROLE_MODERATOR']);
            //$this->addFlash('success', $user->getPseudo()." has been upgrade");
        }

        $userRepository->save($user, true);

        return $this->redirect('/admin');
    }
}
