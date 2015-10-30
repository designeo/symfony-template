DesigneoDumpBundle
=====================

Bundle defines dump() functions for PHP and Twig on production environment - no server error, empty output

## Install

* Register bundle in AppKernel for production environment:

	if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        //...
    }
    else {
        $bundles[] = new \Designeo\DumpBundle\DesigneoDumpBundle();
    }
