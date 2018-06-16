<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController;

use Model\Asset;
use Model\AssetStatus;
use WebPresentation\ApplicationController\DomainCommand\NullAssetCommand;
use WebPresentation\ApplicationController\DomainCommand\LeaseDamageCommand;
use WebPresentation\ApplicationController\DomainCommand\InventoryDamageCommand;
use WebPresentation\ApplicationController\DomainCommand\GatherReturnDetailsCommand;

class AssetApplicationController implements ApplicationController
{
    private $events = [];

    public function __construct()
    {
        $this->load();
    }

    public function getDomainCommand(string $commandString, array $params): DomainCommand
    {
        return $this->getResponse($commandString, $this->getAssetStatus($params))
            ->getDomainCommand();
    }

    public function getView(string $commandString, array $params): string
    {
        return $this->getResponse($commandString, $this->getAssetStatus($params))
            ->getViewUrl();
    }

    public function addResponse(
        string $event,
        string $state,
        string $domainCommand,
        string $view
    ) {
        $response = new Response($domainCommand, $view);
        if (!array_key_exists($event, $this->events)) {
            $this->events[$event] = [];
        }
        $this->events[$event][$state] = $response;
    }

    private function getResponse(string $commandString, string $state): Response
    {
        return $this->getResponseMap($commandString)[$state];
    }

    private function getResponseMap(string $key): array
    {
        return $this->events[$key];
    }

    private function getAssetStatus(array $params): string
    {
        $id = $params["assetID"] ?? null;
        $asset = Asset::find($id);

        return $asset->getAssetStatus();
    }

    private function load(): void
    {
        $this->addResponse("return", AssetStatus::ON_LEASE, GatherReturnDetailsCommand::class, __DIR__ . "/templates/return");
        $this->addResponse("return", AssetStatus::IN_INVENTORY, NullAssetCommand::class, __DIR__ . "/templates/illegalAction");
        $this->addResponse("damage", AssetStatus::ON_LEASE, InventoryDamageCommand::class, __DIR__ . "/templates/leaseDamage");
        $this->addResponse("damage", AssetStatus::IN_INVENTORY, LeaseDamageCommand::class, __DIR__ . "/templates/inventoryDamage");
    }
}
