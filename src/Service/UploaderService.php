<?php

namespace App\Service;

use App\Interface\FileInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderService implements FileInterface
{
    /**     
     *
     * @param SluggerInterface $slugger
     */
    public function __construct(private SluggerInterface $slugger)
    {        
        $this->slugger = $slugger;
    }

     
    /**    
     *
     * @param string|null $directory
     * @param UploadedFile $file
     * @return string|null
     */
    public function upload(?string $directory, UploadedFile $file): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename .'-'. uniqid().'.'.$file->guessExtension();

        try {
            $file->move($directory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**     
     *
     * @param string|null $filename
     * @return void
     */
    public function remove(?string $filename): void
    {        
        $filesystem = new Filesystem();        
        $filesystem->remove([$filename]);
    }      
}
