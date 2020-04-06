<?php


namespace App\Domain\Post;

use JsonSerializable;

class Post implements JsonSerializable
{
    private $id;
    private $content;

    public function __construct(?int $id, ?string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function validate(): bool
    {
        $allowedTags='<p><strong><a><em><u><h1><h2><h3><h4><h5><h6><img>';
        $allowedTags.='<li><ol><ul><span><div><br><ins><del>';

        if ($this->content != '') {
            $this->content = strip_tags($this->content, $allowedTags);
        }

        $isValid = is_int($this->id) || $this->id === null;
        $isValid = $isValid && is_string($this->content);

        return $isValid;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
        ];
    }
}