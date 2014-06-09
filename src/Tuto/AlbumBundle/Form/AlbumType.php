<?php

namespace Tuto\AlbumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array('label' => "Title"));
        $builder->add('artist', 'text', array('label' => "Artist"));
    }

    public function getName()
    {
        return 'album';
    }
}
